<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__.'/../config/connection.php';
$uid=(int)($_SESSION['user_id']??0);
if(!$uid){echo json_encode(['authenticated'=>false,'addresses'=>[],'payment_methods'=>[]]);exit;}
$addresses=[];$s=$conn->prepare('SELECT id,label,full_name,phone,address_line,subdistrict,district,province,postal_code,country,is_default FROM addresses WHERE user_id=? ORDER BY is_default DESC,id DESC');$s->bind_param('i',$uid);$s->execute();$r=$s->get_result();while($row=$r->fetch_assoc())$addresses[]=$row;
$payments=[];$s=$conn->prepare('SELECT id,provider,brand,last4,expiry_month,expiry_year,is_default FROM payment_methods WHERE user_id=? ORDER BY is_default DESC,id DESC');$s->bind_param('i',$uid);$s->execute();$r=$s->get_result();while($row=$r->fetch_assoc())$payments[]=$row;
$s=$conn->prepare('SELECT name,email,phone FROM users WHERE id=?');$s->bind_param('i',$uid);$s->execute();$user=$s->get_result()->fetch_assoc();
echo json_encode(['authenticated'=>true,'user'=>$user,'addresses'=>$addresses,'payment_methods'=>$payments],JSON_UNESCAPED_UNICODE);
