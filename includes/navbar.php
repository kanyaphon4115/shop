<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$inShopping = strpos(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''), '/shopping/') !== false;
$rootPath = $inShopping ? '../' : '';
$shopPath = $inShopping ? '' : 'shopping/';
?>

<div class="w-full px-10 flex justify-between items-center py-4">

<div class="flex gap-8 font-semibold text-sm">

<a href="<?php echo $rootPath; ?>index.php" class="hover:text-orange-500">HOME</a>
<a href="<?php echo $shopPath; ?>products.php" class="hover:text-orange-500">SHOP</a>
<a href="<?php echo $shopPath; ?>men.php" class="hover:text-orange-500">MEN</a>
<a href="<?php echo $shopPath; ?>women.php" class="hover:text-orange-500">WOMEN</a>
<a href="<?php echo $shopPath; ?>kids.php" class="hover:text-orange-500">KIDS</a>
<a href="<?php echo $shopPath; ?>sale.php" class="hover:text-orange-500">SALE</a>
<a href="<?php echo $shopPath; ?>blog.php" class="hover:text-orange-500">BLOG</a>

</div>

<div class="flex gap-6 items-center">

<?php if (!empty($_SESSION['user_name'])) : ?>
    <span class="text-sm">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="<?php echo $shopPath; ?>logout.php" class="text-sm text-orange-500 hover:text-orange-600">Logout</a>
<?php else : ?>
    <button onclick="openLogin()" class="text-sm">Login</button>
<button onclick="openSignup()" class="bg-orange-500 text-white px-4 py-1 rounded">
    Sign Up
</button><?php endif; ?>

<a href="<?php echo $shopPath; ?>cart.php" class="relative text-xl" aria-label="Shopping cart">
    🛒
    <span id="cartBadge" class="absolute -right-3 -top-3 hidden min-w-5 rounded-full bg-orange-500 px-1 text-center text-xs leading-5 text-white">0</span>
</a>

</div>

</div>

<script>
(function updateSparkCartBadge() {
    try {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const count = cart.reduce((total, item) => total + Math.max(1, Number(item.quantity) || 1), 0);
        const badge = document.getElementById('cartBadge');
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        }
    } catch (error) {
        localStorage.removeItem('cart');
    }
})();
</script>

