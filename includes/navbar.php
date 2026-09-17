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
$languageUserId = (int) ($_SESSION['user_id'] ?? 0);
if ($languageUserId && (int) ($_SESSION['language_user_id'] ?? 0) !== $languageUserId && isset($conn) && $conn instanceof mysqli) {
    $languageStmt = $conn->prepare('SELECT language FROM user_settings WHERE user_id=? LIMIT 1');
    $languageStmt->bind_param('i', $languageUserId);
    $languageStmt->execute();
    $languageRow = $languageStmt->get_result()->fetch_assoc();
    $_SESSION['language'] = in_array($languageRow['language'] ?? '', ['en', 'th'], true) ? $languageRow['language'] : 'en';
    $_SESSION['language_user_id'] = $languageUserId;
}
$siteLanguage = in_array($_SESSION['language'] ?? '', ['en', 'th'], true) ? $_SESSION['language'] : 'en';
?>

<div class="flex w-full flex-col items-center justify-between gap-4 px-4 py-1 sm:px-6 lg:flex-row lg:px-10">

<div class="flex max-w-full items-center gap-5 overflow-x-auto whitespace-nowrap pb-2 text-xs font-bold tracking-[.08em] text-stone-700 sm:gap-8 sm:text-sm lg:pb-0">

<?php foreach([['index.php','HOME',$rootPath.'index.php'],['products.php','SHOP',$shopPath.'products.php'],['men.php','MEN',$shopPath.'men.php'],['women.php','WOMEN',$shopPath.'women.php'],['kids.php','KIDS',$shopPath.'kids.php'],['sale.php','SALE',$shopPath.'sale.php'],['blog.php','BLOG',$shopPath.'blog.php']] as $menu): $active=$currentPage===$menu[0]; ?>
<a href="<?php echo $menu[2]; ?>" class="relative py-2 transition hover:text-[#a87b24] <?php echo $active?'text-[#9b701c] after:absolute after:inset-x-0 after:bottom-0 after:h-0.5 after:rounded-full after:bg-[#b58a32]':''; ?>" <?php echo $active?'aria-current="page"':''; ?>><?php echo $menu[1]; ?></a><?php endforeach; ?>

</div>

<div class="flex items-center gap-5 sm:gap-6">

<?php if (!empty($_SESSION['user_name'])) : ?>
    <a href="<?php echo $rootPath; ?>profile/" class="text-sm font-semibold text-stone-700 transition hover:text-[#a87b24]">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
    <a href="<?php echo $shopPath; ?>logout.php" class="text-sm font-semibold text-[#a87b24] hover:text-[#735017]">Logout</a>
<?php else : ?>
    <button onclick="openLogin()" class="text-sm font-semibold text-stone-700 transition hover:text-[#a87b24]">Login</button>
<button onclick="openSignup()" class="rounded-full bg-gradient-to-r from-[#b88a2e] to-[#805719] px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    Sign Up
</button><?php endif; ?>

<a href="<?php echo $shopPath; ?>cart.php" class="relative grid h-10 w-10 place-items-center rounded-full border border-[#b58a32]/30 bg-white text-[#87601d] shadow-sm transition hover:-translate-y-0.5 hover:border-[#b58a32] hover:shadow-md" aria-label="Shopping cart">
    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.4L20 8H7"></path><circle cx="10" cy="20" r="1"></circle><circle cx="17" cy="20" r="1"></circle></svg>
    <span id="cartBadge" class="absolute -right-2 -top-2 hidden min-w-5 rounded-full bg-[#a87b24] px-1 text-center text-xs leading-5 text-white shadow-sm">0</span>
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
<script>window.SPARK_LANGUAGE=<?php echo json_encode($siteLanguage); ?>;</script>
<script src="<?php echo $rootPath; ?>assets/i18n.js"></script>

