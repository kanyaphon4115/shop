<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$inShopping = strpos(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''), '/shopping/') !== false;
$rootPath = $inShopping ? '../' : '';
$shopPath = $inShopping ? '' : 'shopping/';
$searchCategory = $searchCategory ?? 'all';
?>
<header class="bg-white shadow-sm">
<div class="flex items-center justify-between gap-4 px-4 py-4 md:px-10">
<a href="<?php echo $rootPath; ?>index.php" class="shrink-0 text-3xl font-bold">SPARK</a>
<form action="<?php echo $shopPath; ?>search.php" method="get" class="flex min-w-0 flex-1 md:max-w-2xl">
<select name="category" class="hidden rounded-l border px-3 sm:block" aria-label="Product category">
<?php foreach (['all'=>'All Categories','men'=>'Men','women'=>'Women','kids'=>'Kids','sneakers'=>'Sneakers','sale'=>'Sale'] as $value=>$label): ?>
<option value="<?php echo $value; ?>" <?php echo $searchCategory === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
<?php endforeach; ?>
</select>
<input name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES); ?>" class="min-w-0 flex-1 rounded-l border px-3 py-2 sm:rounded-none" placeholder="Search products" aria-label="Search products">
<button class="rounded-r bg-blue-500 px-5 text-white hover:bg-blue-600" aria-label="Search">🔍</button>
</form></div></header>
<nav class="overflow-x-auto border-t bg-white"><div class="min-w-[720px]"><?php include __DIR__ . '/navbar.php'; ?></div></nav>
<div id="loginModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"><div class="relative w-full max-w-sm rounded-xl bg-white p-6"><button onclick="closeLogin()" class="absolute right-4 top-3 text-xl">×</button><h2 class="mb-5 text-center text-2xl font-bold">Login</h2><form action="<?php echo $rootPath; ?>login_process.php" method="post"><input name="email" class="mb-3 w-full rounded border px-4 py-3" placeholder="Email or Phone" required><input type="password" name="password" class="mb-4 w-full rounded border px-4 py-3" placeholder="Password" required><button class="w-full rounded bg-orange-500 py-3 font-semibold text-white">LOGIN</button></form><button onclick="openSignup()" class="mt-4 w-full text-sm text-blue-600">Create an account</button></div></div>
<div id="signupModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"><div class="relative w-full max-w-sm rounded-xl bg-white p-6"><button onclick="closeSignup()" class="absolute right-4 top-3 text-xl">×</button><h2 class="mb-5 text-center text-2xl font-bold">Sign Up</h2><form action="<?php echo $shopPath; ?>register_process.php" method="post"><input name="username" class="mb-3 w-full rounded border px-4 py-3" placeholder="Username"><input type="email" name="email" class="mb-3 w-full rounded border px-4 py-3" placeholder="Email" required><input type="password" name="password" class="mb-4 w-full rounded border px-4 py-3" placeholder="Password" required><button class="w-full rounded bg-orange-500 py-3 font-semibold text-white">SIGN UP</button></form></div></div>
<script>
function sparkModal(id,show){const el=document.getElementById(id);if(!el)return;el.classList.toggle('hidden',!show);el.classList.toggle('flex',show)}
function openLogin(){sparkModal('signupModal',false);sparkModal('loginModal',true)} function closeLogin(){sparkModal('loginModal',false)}
function openSignup(){sparkModal('loginModal',false);sparkModal('signupModal',true)} function closeSignup(){sparkModal('signupModal',false)}
</script>
