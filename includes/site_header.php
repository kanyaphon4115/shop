<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$inShopping = strpos($scriptPath, '/shopping/') !== false;
$inProfile = strpos($scriptPath, '/profile/') !== false;
$profileDepth = $inProfile ? substr_count(trim(substr($scriptPath, strpos($scriptPath, '/profile/') + 9), '/'), '/') + 1 : 0;
$rootPath = $inShopping ? '../' : ($inProfile ? str_repeat('../', $profileDepth) : '');
$shopPath = $inShopping ? '' : $rootPath . 'shopping/';
$searchCategory = $searchCategory ?? 'all';
?>
<style>@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700;800&display=swap');.site-header,.site-header *{font-family:Manrope,'Noto Sans Thai',sans-serif}.site-header .site-wordmark{font-family:'DM Serif Display',serif;letter-spacing:.04em}</style>
<header class="site-header border-b border-[#b58a32]/20 bg-[#fcfbf8]/95 shadow-[0_5px_22px_rgba(45,36,18,.06)] backdrop-blur">
<div class="mx-auto flex w-full max-w-[1440px] items-center justify-between gap-4 px-4 py-4 sm:px-6 md:px-10">
<a href="<?php echo $rootPath; ?>index.php" class="group flex shrink-0 items-center gap-3" aria-label="SPARK home"><span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-[#c9a24c] via-[#a87b24] to-[#60410e] text-lg font-black text-white shadow-[0_7px_16px_rgba(119,82,22,.24)] transition group-hover:-translate-y-0.5">S</span><span class="hidden sm:block"><span class="site-wordmark block text-3xl leading-none text-stone-950">SPARK</span><span class="mt-1 block text-[8px] font-bold tracking-[.32em] text-[#a87b24]">CURATED FOOTWEAR</span></span></a>
<form action="<?php echo $shopPath; ?>search.php" method="get" class="flex min-w-0 flex-1 overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm transition focus-within:border-[#b58a32] focus-within:ring-4 focus-within:ring-[#b58a32]/10 md:max-w-2xl">
<select name="category" class="hidden border-r border-stone-200 bg-[#f8f6f0] px-4 py-3 text-sm font-semibold text-stone-700 outline-none sm:block" aria-label="Product category">
<?php foreach (['all'=>'All Categories','men'=>'Men','women'=>'Women','kids'=>'Kids','sneakers'=>'Sneakers','sale'=>'Sale'] as $value=>$label): ?>
<option value="<?php echo $value; ?>" <?php echo $searchCategory === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
<?php endforeach; ?>
</select>
<input name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES); ?>" class="min-w-0 flex-1 bg-transparent px-4 py-3 text-sm text-stone-800 outline-none placeholder:text-stone-400" placeholder="Search products" aria-label="Search products">
<button class="flex items-center justify-center bg-gradient-to-br from-[#b88a2e] to-[#785215] px-5 text-white transition hover:from-[#c89e43] hover:to-[#8c621b]" aria-label="Search"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg></button>
</form><?php if (!empty($headerNote)): ?><span class="hidden shrink-0 text-sm font-semibold text-[#87601d] lg:block"><?php echo htmlspecialchars($headerNote, ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?></div></header>
<nav class="site-header overflow-x-auto border-b border-[#b58a32]/15 bg-[#fcfbf8]"><div class="mx-auto min-w-[720px] max-w-[1440px] py-3"><?php include __DIR__ . '/navbar.php'; ?></div></nav>
<div id="loginModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"><div class="relative w-full max-w-sm rounded-xl bg-white p-6"><button onclick="closeLogin()" class="absolute right-4 top-3 text-xl">×</button><h2 class="mb-5 text-center text-2xl font-bold">Login</h2><form action="<?php echo $rootPath; ?>login_process.php" method="post"><input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? ($rootPath.'index.php'), ENT_QUOTES, 'UTF-8'); ?>"><input name="email" class="mb-3 w-full rounded border px-4 py-3" placeholder="Email or Phone" required><input type="password" name="password" class="mb-4 w-full rounded border px-4 py-3" placeholder="Password" required><button class="w-full rounded bg-orange-500 py-3 font-semibold text-white">LOGIN</button></form><button onclick="openSignup()" class="mt-4 w-full text-sm text-blue-600">Create an account</button></div></div>
<div id="signupModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"><div class="relative w-full max-w-sm rounded-xl bg-white p-6"><button onclick="closeSignup()" class="absolute right-4 top-3 text-xl">×</button><h2 class="mb-5 text-center text-2xl font-bold">Sign Up</h2><form action="<?php echo $shopPath; ?>register_process.php" method="post"><input name="username" class="mb-3 w-full rounded border px-4 py-3" placeholder="Username"><input type="email" name="email" class="mb-3 w-full rounded border px-4 py-3" placeholder="Email" required><input type="password" name="password" class="mb-4 w-full rounded border px-4 py-3" placeholder="Password" required><button class="w-full rounded bg-orange-500 py-3 font-semibold text-white">SIGN UP</button></form></div></div>
<script>
function sparkModal(id,show){const el=document.getElementById(id);if(!el)return;el.classList.toggle('hidden',!show);el.classList.toggle('flex',show)}
function openLogin(){sparkModal('signupModal',false);sparkModal('loginModal',true)} function closeLogin(){sparkModal('loginModal',false)}
function openSignup(){sparkModal('loginModal',false);sparkModal('signupModal',true)} function closeSignup(){sparkModal('signupModal',false)}
</script>
