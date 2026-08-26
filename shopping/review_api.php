<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../config/connection.php';

function respond(bool $ok,string $message,array $data=[],int $status=200): void { http_response_code($status);echo json_encode(array_merge(['ok'=>$ok,'message'=>$message],$data),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit; }
function currentUser(mysqli $conn): array {
    $id=(int)($_SESSION['user_id']??0);
    if(!$id&&!empty($_SESSION['email'])){$s=mysqli_prepare($conn,'SELECT id,name FROM users WHERE email=? LIMIT 1');mysqli_stmt_bind_param($s,'s',$_SESSION['email']);mysqli_stmt_execute($s);$u=mysqli_stmt_get_result($s)->fetch_assoc();$id=(int)($u['id']??0);if($id)$_SESSION['user_id']=$id;}
    if(!$id)return [];$s=mysqli_prepare($conn,'SELECT id,name FROM users WHERE id=? LIMIT 1');mysqli_stmt_bind_param($s,'i',$id);mysqli_stmt_execute($s);return mysqli_stmt_get_result($s)->fetch_assoc()?:[];
}
$action=$_GET['action']??$_POST['action']??'list'; $productId=(int)($_GET['product_id']??$_POST['product_id']??0);
$ps=mysqli_prepare($conn,'SELECT id FROM products WHERE id=?');mysqli_stmt_bind_param($ps,'i',$productId);mysqli_stmt_execute($ps);if(!mysqli_stmt_get_result($ps)->fetch_assoc())respond(false,'Product not found.',[],404);

if($action==='list'){
    $sort=$_GET['sort']??'recent';$rating=max(0,min(5,(int)($_GET['rating']??0)));$page=max(1,(int)($_GET['page']??1));$limit=5;$offset=($page-1)*$limit;
    $orders=['recent'=>'r.created_at DESC','highest'=>'r.rating DESC,r.created_at DESC','lowest'=>'r.rating ASC,r.created_at DESC','helpful'=>'helpful_count DESC,r.created_at DESC'];$order=$orders[$sort]??$orders['recent'];
    $where='r.product_id=?'.($rating?' AND r.rating=?':'');$countSql="SELECT COUNT(*) total FROM reviews r WHERE $where";$cs=mysqli_prepare($conn,$countSql);if($rating)mysqli_stmt_bind_param($cs,'ii',$productId,$rating);else mysqli_stmt_bind_param($cs,'i',$productId);mysqli_stmt_execute($cs);$total=(int)mysqli_stmt_get_result($cs)->fetch_assoc()['total'];
    $sql="SELECT r.id,r.rating,r.title,r.comment,r.images_json,r.created_at,u.name user_name,COUNT(DISTINCT h.user_id) helpful_count,EXISTS(SELECT 1 FROM orders o JOIN order_items oi ON oi.order_id=o.id WHERE o.user_id=r.user_id AND oi.product_id=r.product_id) verified FROM reviews r JOIN users u ON u.id=r.user_id LEFT JOIN review_helpful h ON h.review_id=r.id WHERE $where GROUP BY r.id ORDER BY $order LIMIT ? OFFSET ?";
    $s=mysqli_prepare($conn,$sql);if($rating)mysqli_stmt_bind_param($s,'iiii',$productId,$rating,$limit,$offset);else mysqli_stmt_bind_param($s,'iii',$productId,$limit,$offset);mysqli_stmt_execute($s);$res=mysqli_stmt_get_result($s);$reviews=[];while($r=mysqli_fetch_assoc($res)){$r['images']=json_decode($r['images_json']?:'[]',true)?:[];unset($r['images_json']);$reviews[]=$r;}
    $ss=mysqli_prepare($conn,'SELECT COUNT(*) count,COALESCE(AVG(rating),0) average,SUM(rating=5) five,SUM(rating=4) four,SUM(rating=3) three,SUM(rating=2) two,SUM(rating=1) one FROM reviews WHERE product_id=?');mysqli_stmt_bind_param($ss,'i',$productId);mysqli_stmt_execute($ss);$summary=mysqli_stmt_get_result($ss)->fetch_assoc();respond(true,'Reviews loaded.',['reviews'=>$reviews,'summary'=>$summary,'page'=>$page,'pages'=>(int)ceil($total/$limit)]);
}

$user=currentUser($conn);if(!$user)respond(false,'Please login to write a review.',[],401);
$token=$_SERVER['HTTP_X_CSRF_TOKEN']??($_POST['csrf_token']??'');if(empty($_SESSION['review_csrf'])||!hash_equals($_SESSION['review_csrf'],$token))respond(false,'Your session expired. Please refresh and try again.',[],419);
if($action==='helpful'){
    $reviewId=(int)($_POST['review_id']??0);$s=mysqli_prepare($conn,'INSERT IGNORE INTO review_helpful(review_id,user_id) SELECT id,? FROM reviews WHERE id=? AND product_id=?');mysqli_stmt_bind_param($s,'iii',$user['id'],$reviewId,$productId);mysqli_stmt_execute($s);$c=mysqli_prepare($conn,'SELECT COUNT(*) count FROM review_helpful WHERE review_id=?');mysqli_stmt_bind_param($c,'i',$reviewId);mysqli_stmt_execute($c);$count=(int)mysqli_stmt_get_result($c)->fetch_assoc()['count'];respond(true,mysqli_affected_rows($conn)?'Marked helpful.':'You already marked this review helpful.',['count'=>$count]);
}
if($action!=='create'||$_SERVER['REQUEST_METHOD']!=='POST')respond(false,'Method not allowed.',[],405);
$rating=(int)($_POST['rating']??0);$title=trim($_POST['title']??'');$comment=trim($_POST['comment']??'');if($rating<1||$rating>5)respond(false,'Please select a rating from 1 to 5.',[],422);if($comment==='')respond(false,'Review text cannot be empty.',[],422);if(mb_strlen($comment)>5000||mb_strlen($title)>160)respond(false,'Review content is too long.',[],422);
$images=[];$files=$_FILES['images']??null;if($files&&is_array($files['name'])){if(count($files['name'])>3)respond(false,'You can upload up to 3 images.',[],422);$allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];$dir=__DIR__.'/../assets/uploads/reviews';if(!is_dir($dir)&&!mkdir($dir,0755,true))respond(false,'Upload directory is unavailable.',[],500);$finfo=new finfo(FILEINFO_MIME_TYPE);foreach($files['name'] as $i=>$name){if($files['error'][$i]===UPLOAD_ERR_NO_FILE)continue;if($files['error'][$i]!==UPLOAD_ERR_OK||$files['size'][$i]>3*1024*1024)respond(false,'Each image must be 3 MB or smaller.',[],422);$mime=$finfo->file($files['tmp_name'][$i]);if(!isset($allowed[$mime]))respond(false,'Only JPEG, PNG and WebP images are allowed.',[],422);$file=bin2hex(random_bytes(16)).'.'.$allowed[$mime];if(!move_uploaded_file($files['tmp_name'][$i],$dir.'/'.$file))respond(false,'An image could not be saved.',[],500);$images[]=$file;}}
$json=json_encode($images);$s=mysqli_prepare($conn,'INSERT INTO reviews(product_id,user_id,rating,title,comment,images_json) VALUES(?,?,?,?,?,?)');mysqli_stmt_bind_param($s,'iiisss',$productId,$user['id'],$rating,$title,$comment,$json);mysqli_stmt_execute($s);respond(true,'Thank you for your review.',['review_id'=>mysqli_insert_id($conn)]);
