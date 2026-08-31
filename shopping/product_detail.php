<?php
session_start();


include __DIR__ . "/../config/connection.php";

// ✅ กันพัง + กัน hack
$id = intval($_GET['id'] ?? 0);

// ✅ query product
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Sale pricing is optional for compatibility with databases that have not run the latest migration.
$productColumns = [];
foreach (mysqli_query($conn, 'SHOW COLUMNS FROM products') as $column) {
    $productColumns[$column['Field']] = true;
}
$displayPrice = (float) $row['price'];
if (!empty($row['on_sale'])) {
    $displayPrice = isset($productColumns['sale_price']) && !empty($row['sale_price'])
        ? (float) $row['sale_price']
        : round($displayPrice * 0.8, 2);
}

// ✅ query images
$sql_img = "SELECT * FROM product_images WHERE product_id = $id";
$result_img = mysqli_query($conn, $sql_img);
$gallery_images = [];
while ($image_row = mysqli_fetch_assoc($result_img)) {
    $gallery_images[] = $image_row['image'];
}
if (!$gallery_images) {
    $gallery_images[] = $row['image'];
}
$reviewStmt = mysqli_prepare($conn, 'SELECT COUNT(*) review_count, COALESCE(AVG(rating),0) average_rating FROM reviews WHERE product_id = ?');
mysqli_stmt_bind_param($reviewStmt, 'i', $id);
mysqli_stmt_execute($reviewStmt);
$reviewSummary = mysqli_stmt_get_result($reviewStmt)->fetch_assoc();
$reviewCount = (int) $reviewSummary['review_count'];
$averageRating = (float) $reviewSummary['average_rating'];
$_SESSION['review_csrf'] = $_SESSION['review_csrf'] ?? bin2hex(random_bytes(32));
$reviewCsrf = $_SESSION['review_csrf'];
$reviewLoggedIn = !empty($_SESSION['user_id']) || !empty($_SESSION['email']);
$purchaseLoggedIn = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $row['name']; ?></title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
<!-- HEADER -->

<header class="bg-white shadow">

<div class="w-full px-10 flex items-center justify-between py-4">
<h1 class="text-3xl font-bold">
SPARK
</h1>

<form action="search.php" method="get" class="flex w-1/2">

<select name="category" class="border px-3 py-2 rounded-l" aria-label="Product category">
<option value="all">All Categories</option>
<option value="men">Men</option><option value="women">Women</option><option value="kids">Kids</option><option value="sneakers">Sneakers</option><option value="sale">Sale</option>
</select>

<input
name="q"
class="flex-1 border px-4 py-2"
placeholder="Search for more than 20,000 products">

<button type="submit" class="bg-blue-500 text-white px-6 rounded-r flex items-center justify-center hover:bg-blue-600 transition" aria-label="Search">
🔍
</button>

</form>


</div>

</header>

<!-- NAV -->
<?php include "../includes/navbar.php"; ?>

<!-- PRODUCT DETAIL -->
<section class="mx-auto mt-10 grid max-w-7xl gap-10 px-4 md:grid-cols-2 md:px-10">

<!-- LEFT: IMAGES -->
<div class="flex gap-4">

<!-- thumbnails -->
<div class="flex flex-col gap-3">

<?php foreach ($gallery_images as $gallery_image) { ?>

<img src="../assets/images/<?php echo htmlspecialchars($gallery_image); ?>"
class="gallery-thumb w-20 h-20 object-cover border-2 cursor-pointer rounded transition hover:border-orange-400 <?php echo $gallery_image === $row['image'] ? 'border-orange-500 ring-2 ring-orange-100' : 'border-gray-200'; ?>"
onclick="changeImage(this)">

<?php } ?>

</div>



<!-- main image -->
<div class="min-w-0 flex-1">
<img 
id="mainImage"
src="../assets/images/<?php echo $row['image']; ?>"
class="w-full h-[400px] object-contain bg-white rounded shadow">
</div>

</div>

<!-- RIGHT: INFO -->
<div>

<h1 class="text-3xl font-bold mb-3">
<?php echo $row['name']; ?>
</h1>

<div class="mb-2 flex flex-wrap items-center gap-2 text-xl text-yellow-400">
<span id="titleStars"><?php echo str_repeat('★',(int)round($averageRating)).str_repeat('☆',5-(int)round($averageRating)); ?></span> <strong id="titleAverage" class="text-base text-gray-700"><?php echo number_format($averageRating,1); ?></strong>
<button id="titleReviewCount" onclick="openReviewTab()" class="text-sm text-gray-500 underline-offset-2 hover:text-orange-500 hover:underline">(<?php echo $reviewCount; ?> reviews)</button>
</div>

<p class="text-3xl text-green-600 font-bold mb-4">
<?php if (!empty($row['on_sale'])): ?><span class="mr-2 text-lg font-normal text-gray-400 line-through">$<?php echo number_format((float)$row['price'], 2); ?></span><?php endif; ?>
$<?php echo number_format($displayPrice, 2); ?>
</p>


<!-- size -->
<div class="mb-4">
<p class="font-semibold mb-2">Size:</p>

<div class="flex gap-2">

<button onclick="selectSize(this)" 
class="size-btn border px-3 py-1 rounded">
S
</button>

<button onclick="selectSize(this)" 
class="size-btn border px-3 py-1 rounded">
M
</button>

<button onclick="selectSize(this)" 
class="size-btn border px-3 py-1 rounded">
L
</button>

</div>

<!-- เก็บค่าที่เลือก -->
<input type="hidden" id="selectedSize" name="size">

</div>

<!-- button -->
<button onclick="addToCart()" 
class="bg-orange-500 text-white px-6 py-3 rounded">
Add to Cart
</button>
<div class="flex gap-4 mt-4">



<button onclick="buyNow()" 
class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800">
Buy Now
</button>

</div>
</div>

</section>

<?php include __DIR__ . '/../includes/review_section.php'; ?>

<!-- FOOTER -->
<?php include "../includes/footer.php"; ?>
<script>
function changeImage(element){
    document.getElementById("mainImage").src = element.src;
    document.querySelectorAll(".gallery-thumb").forEach(thumbnail => {
        thumbnail.classList.remove("border-orange-500", "ring-2", "ring-orange-100");
        thumbnail.classList.add("border-gray-200");
    });
    element.classList.remove("border-gray-200");
    element.classList.add("border-orange-500", "ring-2", "ring-orange-100");
}
</script>
<script>
function selectSize(el){

    // ลบสีทุกปุ่ม
    document.querySelectorAll(".size-btn").forEach(btn=>{
        btn.classList.remove("bg-orange-500","text-white")
    })

    // ใส่สีปุ่มที่เลือก
    el.classList.add("bg-orange-500","text-white")

    // เก็บค่า size
    document.getElementById("selectedSize").value = el.innerText
}
</script>
<script>
function buyNow(){

    if (!<?php echo $purchaseLoggedIn ? 'true' : 'false'; ?>) {
        alert('Please login to continue shopping.');
        window.location.href = '../index.php?login=1&redirect=' + encodeURIComponent(window.location.pathname + window.location.search);
        return;
    }

    let size = document.getElementById("selectedSize").value

    if(size === ""){
        alert("กรุณาเลือก Size ก่อน")
        return
    }

    // ไปหน้า checkout พร้อมส่งค่า
    window.location.href = "checkout.php?size=" + encodeURIComponent(size) + "&id=<?php echo $row['id']; ?>"
}
</script>
<script>
async function addToCart(){
    if (!<?php echo $purchaseLoggedIn ? 'true' : 'false'; ?>) {
        alert('Please login to continue shopping.');
        window.location.href = '../index.php?login=1&redirect=' + encodeURIComponent(window.location.pathname + window.location.search);
        return;
    }
    const size = document.getElementById("selectedSize").value;
    if (!size) {
        alert("Please select a size");
        return;
    }

    const product = <?php echo json_encode([
        'id' => (int) $row['id'],
        'name' => $row['name'],
        'image' => $row['image'],
        'price' => $displayPrice
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const existing = cart.find(item => Number(item.id) === Number(product.id) && item.size === size);

    if (existing) {
        existing.quantity = Math.max(1, Number(existing.quantity) || 1) + 1;
    } else {
        cart.push({...product, size, quantity: 1});
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    window.location.href = "cart.php";
}
</script>
<script>window.SPARK_REVIEW=<?php echo json_encode(['productId'=>$id,'csrf'=>$reviewCsrf,'loggedIn'=>$reviewLoggedIn],JSON_UNESCAPED_SLASHES); ?>;</script>
<script src="../assets/reviews.js"></script>

</body>
</html>
