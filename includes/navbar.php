<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$inShopping = strpos($scriptPath, '/shopping/') !== false;
$inProfile = strpos($scriptPath, '/profile/') !== false;
$profileDepth = $inProfile ? substr_count(trim(substr($scriptPath, strpos($scriptPath, '/profile/') + 9), '/'), '/') + 1 : 0;
$rootPath = $inShopping ? '../' : ($inProfile ? str_repeat('../', $profileDepth) : '');
$shopPath = $inShopping ? '' : $rootPath . 'shopping/';
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
?>

<div class="flex w-full flex-col items-center justify-between gap-4 px-4 py-1 sm:px-6 lg:flex-row lg:px-10">

<div class="flex max-w-full items-center gap-5 overflow-x-auto whitespace-nowrap pb-2 text-xs font-semibold sm:gap-8 sm:text-sm lg:pb-0">

<?php foreach([['index.php','HOME',$rootPath.'index.php'],['products.php','SHOP',$shopPath.'products.php'],['men.php','MEN',$shopPath.'men.php'],['women.php','WOMEN',$shopPath.'women.php'],['kids.php','KIDS',$shopPath.'kids.php'],['sale.php','SALE',$shopPath.'sale.php'],['blog.php','BLOG',$shopPath.'blog.php']] as $menu): $active=$currentPage===$menu[0]; ?>
<a href="<?php echo $menu[2]; ?>" class="relative py-2 transition hover:text-orange-500 <?php echo $active?'text-orange-500 after:absolute after:inset-x-0 after:bottom-0 after:h-0.5 after:rounded-full after:bg-orange-500':''; ?>" <?php echo $active?'aria-current="page"':''; ?>><?php echo $menu[1]; ?></a><?php endforeach; ?>

</div>

<div class="flex items-center gap-5 sm:gap-6">

<?php if (!empty($_SESSION['user_name'])) : ?>
    <a href="<?php echo $rootPath; ?>profile/" class="text-sm hover:text-orange-500">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
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

