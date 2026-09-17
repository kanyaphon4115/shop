<?php
session_start();
include __DIR__ . "/config/connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Spark Shop</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
html,body{overflow-x:hidden;font-family:Manrope,'Noto Sans Thai',sans-serif}.brand-wordmark{font-family:'DM Serif Display',serif;letter-spacing:.04em}.hero-slide{opacity:0;pointer-events:none;transition:opacity .75s ease}.hero-slide.is-active{opacity:1;pointer-events:auto}.hero-copy>*{opacity:0;transform:translateX(-28px);transition:opacity .65s ease,transform .65s ease}.hero-visual{opacity:0;transform:translateX(38px) scale(.97);transition:opacity .8s ease,transform .8s cubic-bezier(.22,1,.36,1)}.hero-slide.is-active .hero-copy>*{opacity:1;transform:none}.hero-slide.is-active .hero-copy>*:nth-child(2){transition-delay:.08s}.hero-slide.is-active .hero-copy>*:nth-child(3){transition-delay:.16s}.hero-slide.is-active .hero-copy>*:nth-child(4){transition-delay:.24s}.hero-slide.is-active .hero-visual{opacity:1;transform:none}.hero-dot{transition:width .3s ease,background-color .3s ease}.hero-dot.is-active{width:2rem;background:#f97316}.video-showcase-card{isolation:isolate;transition:transform .35s ease,box-shadow .35s ease}.video-showcase-card:hover{transform:translateY(-4px);box-shadow:0 26px 70px rgba(15,12,7,.34)}.video-showcase-media:after{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:linear-gradient(180deg,rgba(5,5,5,.03) 42%,rgba(5,5,5,.76) 100%)}.video-showcase-media video{transition:transform .7s cubic-bezier(.22,1,.36,1)}.video-showcase-card:hover .video-showcase-media video{transform:scale(1.025)}#trending-products{background:linear-gradient(135deg,#f8f7f3,#eeece5)}.luxury-product-card{position:relative;overflow:hidden;border:1px solid rgba(160,127,55,.22);border-radius:1rem;background:linear-gradient(145deg,#fff,#f9f7f0);box-shadow:0 10px 24px rgba(45,36,18,.08);transition:transform .35s cubic-bezier(.22,1,.36,1),box-shadow .35s ease,border-color .35s ease}.luxury-product-card:before{content:"";position:absolute;z-index:1;inset:0;pointer-events:none;border-radius:inherit;background:linear-gradient(125deg,rgba(255,255,255,.55),transparent 34%)}.luxury-product-card:hover{transform:translateY(-7px);border-color:rgba(165,124,33,.7);box-shadow:0 22px 40px rgba(45,36,18,.2)}.luxury-product-image{background:radial-gradient(circle at 50% 30%,#fff 0,#f7f6f1 64%,#ebe6da 100%)}.luxury-product-card img{transition:transform .55s cubic-bezier(.22,1,.36,1),filter .35s ease}.luxury-product-card:hover img{transform:scale(1.06);filter:drop-shadow(0 14px 12px rgba(30,24,13,.17))}.luxury-product-card:after{content:"";position:absolute;top:0;left:1.25rem;right:1.25rem;height:2px;background:linear-gradient(90deg,transparent,#b58a32,transparent);opacity:.75}@media(prefers-reduced-motion:reduce){.hero-slide,.hero-copy>*,.hero-visual,.hero-dot,.video-showcase-card,.video-showcase-media video,.luxury-product-card,.luxury-product-card img{transition:none!important}.video-showcase-card:hover,.luxury-product-card:hover{transform:none}.video-showcase-card:hover .video-showcase-media video,.luxury-product-card:hover img{transform:none}}
</style>
</head>

<body class="bg-gray-100">

<!-- HEADER -->

<header class="border-b border-[#b58a32]/20 bg-[#fcfbf8]/95 shadow-[0_5px_22px_rgba(45,36,18,.06)] backdrop-blur">

<div class="mx-auto flex w-full max-w-[1440px] flex-col items-center justify-between gap-4 px-4 py-4 sm:px-6 md:flex-row lg:px-10">
<a href="index.php" class="group flex shrink-0 items-center gap-3" aria-label="SPARK home">
<span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-[#c9a24c] via-[#a87b24] to-[#60410e] text-lg font-black text-white shadow-[0_7px_16px_rgba(119,82,22,.24)] transition group-hover:-translate-y-0.5">S</span>
<span><span class="brand-wordmark block text-3xl leading-none text-stone-950">SPARK</span><span class="mt-1 block text-[8px] font-bold tracking-[.32em] text-[#a87b24]">CURATED FOOTWEAR</span></span>
</a>

<form action="shopping/search.php" method="get" class="flex w-full max-w-2xl overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm transition focus-within:border-[#b58a32] focus-within:ring-4 focus-within:ring-[#b58a32]/10 md:w-1/2">

<select name="category" class="hidden border-r border-stone-200 bg-[#f8f6f0] px-4 py-3 text-sm font-semibold text-stone-700 outline-none sm:block" aria-label="Product category">
<option value="all">All Categories</option>
<option value="men">Men</option><option value="women">Women</option><option value="kids">Kids</option><option value="sneakers">Sneakers</option><option value="sale">Sale</option>
</select>

<input
name="q"
class="min-w-0 flex-1 bg-transparent px-4 py-3 text-sm text-stone-800 outline-none placeholder:text-stone-400"
placeholder="Search for more than 20,000 products">

<button type="submit" class="flex items-center justify-center bg-gradient-to-br from-[#b88a2e] to-[#785215] px-5 text-white transition hover:from-[#c89e43] hover:to-[#8c621b]" aria-label="Search">
<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg>
</button>

</form>


</div>

</header>


<nav class="border-b border-[#b58a32]/15 bg-[#fcfbf8]">

<div class="mx-auto max-w-[1440px] py-3">

<?php include "includes/navbar.php"; ?>

</div>

</nav>


<!-- HERO SLIDER -->

<section class="mx-auto mt-5 w-full max-w-[1440px] px-4 sm:px-6 lg:px-10" aria-label="Featured collections">
<div id="heroCarousel" class="relative min-h-[610px] overflow-hidden rounded-3xl bg-gradient-to-br from-white via-gray-50 to-gray-100 shadow-[0_20px_60px_rgba(15,23,42,.08)] md:min-h-[520px]" tabindex="0">
    <div class="pointer-events-none absolute -right-20 -top-28 h-96 w-96 rounded-full bg-orange-100/70 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 left-1/3 h-40 w-80 rounded-full bg-white blur-2xl"></div>
    <?php $heroSlides=[['NEW ARRIVAL','Step Up Your Style','Premium comfort. Timeless design. Made for every move you make.','assets/images/banner1.png','Signature white and orange sneakers'],['EVERYDAY ESSENTIAL','Comfort Meets Confidence','Clean lines, effortless comfort and a fresh look for every day.','assets/images/benner2.png','Classic white sneakers'],['BUILT TO MOVE','Move Without Limits','Responsive support and modern style engineered for your active life.','assets/images/shoes3.png','Performance running sneakers']]; foreach($heroSlides as $index=>$slide): ?>
    <article class="hero-slide absolute inset-0 grid grid-cols-1 items-center gap-2 px-7 pb-16 pt-10 md:grid-cols-2 md:gap-8 md:px-16 md:py-12 lg:px-24 <?php echo $index===0?'is-active':''; ?>" data-slide="<?php echo $index; ?>" aria-hidden="<?php echo $index===0?'false':'true'; ?>">
        <div class="hero-copy relative z-10 max-w-xl text-center md:text-left"><p class="mb-3 text-xs font-extrabold tracking-[.28em] text-orange-500 sm:text-sm"><?php echo $slide[0]; ?></p><h2 class="text-4xl font-black leading-[1.05] tracking-tight text-gray-950 sm:text-5xl lg:text-6xl"><?php echo $slide[1]; ?></h2><p class="mx-auto mt-5 max-w-lg text-base leading-7 text-gray-600 md:mx-0 lg:text-lg"><?php echo $slide[2]; ?></p><div class="mt-7 flex flex-wrap justify-center gap-3 md:justify-start"><a href="shopping/products.php" class="rounded-full bg-orange-500 px-7 py-3 text-sm font-bold text-white shadow-lg shadow-orange-200 transition hover:-translate-y-0.5 hover:bg-orange-600">Shop Now</a><a href="shopping/products.php" class="rounded-full border border-gray-300 bg-white/80 px-7 py-3 text-sm font-bold text-gray-800 transition hover:-translate-y-0.5 hover:border-orange-400 hover:text-orange-600">Explore Collection</a></div></div>
        <div class="hero-visual relative flex min-h-[240px] items-center justify-center md:min-h-[410px]"><div class="absolute h-52 w-52 rounded-full bg-gradient-to-br from-white to-gray-200 shadow-inner sm:h-72 sm:w-72 lg:h-80 lg:w-80"></div><div class="absolute bottom-[12%] h-8 w-3/4 rounded-[50%] bg-gray-900/20 blur-xl"></div><img src="<?php echo $slide[3]; ?>" alt="<?php echo $slide[4]; ?>" class="relative z-10 max-h-[270px] w-[92%] object-contain drop-shadow-[0_25px_18px_rgba(15,23,42,.22)] md:max-h-[410px] lg:w-full" draggable="false"></div>
    </article>
    <?php endforeach; ?>
    <button id="heroPrev" type="button" class="absolute left-3 top-1/2 z-20 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full border border-gray-200 bg-white/90 text-xl text-gray-800 shadow-md transition hover:bg-orange-500 hover:text-white md:left-5" aria-label="Previous slide">&#8592;</button><button id="heroNext" type="button" class="absolute right-3 top-1/2 z-20 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full border border-gray-200 bg-white/90 text-xl text-gray-800 shadow-md transition hover:bg-orange-500 hover:text-white md:right-5" aria-label="Next slide">&#8594;</button>
    <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2" aria-label="Choose hero slide"><?php foreach($heroSlides as $index=>$_): ?><button type="button" class="hero-dot h-2 w-2 rounded-full bg-gray-300 <?php echo $index===0?'is-active':''; ?>" data-slide-to="<?php echo $index; ?>" aria-label="Go to slide <?php echo $index+1; ?>"></button><?php endforeach; ?></div>
</div></section>
<!-- VIDEO SHOWCASE -->
<section class="mx-auto mt-8 w-full max-w-[1180px] px-4 sm:px-6 lg:px-0" aria-labelledby="videoShowcaseTitle">
    <div class="video-showcase-card overflow-hidden rounded-3xl border border-amber-400/60 bg-[#10100f] p-2 shadow-[0_20px_55px_rgba(20,16,8,.26)] sm:p-3">
        <div class="video-showcase-media relative aspect-video overflow-hidden rounded-[1.15rem] bg-[#171512]">
            <?php if (is_file(__DIR__ . '/assets/demo.mp4')): ?>
            <video id="showcaseVideo" class="h-full w-full object-cover" autoplay muted loop playsinline preload="metadata" aria-describedby="videoShowcaseTitle">
                <source src="assets/demo.mp4?v=<?php echo filemtime(__DIR__ . '/assets/demo.mp4'); ?>" type="video/mp4">
                Your browser does not support HTML5 video.
            </video>
            <?php endif; ?>
            <div id="videoFallback" class="absolute inset-0 z-[2] flex <?php echo is_file(__DIR__ . '/assets/demo.mp4') ? 'hidden' : ''; ?> items-center justify-center bg-[radial-gradient(circle_at_75%_20%,rgba(217,170,62,.22),transparent_30%),linear-gradient(135deg,#211c12,#090909)] px-6 text-center">
                <div><span class="mb-4 inline-grid h-14 w-14 place-items-center rounded-full border border-amber-300/60 bg-amber-300/10 text-2xl text-amber-200">▶</span><p class="text-xs font-bold tracking-[.28em] text-amber-300">AEGIS MOTION</p><p class="mt-3 text-lg font-semibold text-white sm:text-xl">Video showcase is ready for your story.</p><p class="mt-2 text-sm text-stone-300">Add <code class="rounded bg-white/10 px-1.5 py-0.5 text-amber-100">assets/demo.mp4</code> to bring it to life.</p></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 z-[3] flex items-end justify-between gap-3 p-4 sm:p-6">
                <div class="max-w-md text-white"><p class="text-[10px] font-bold tracking-[.3em] text-amber-300 sm:text-xs">AEGIS / VIDEO SHOWCASE</p><h2 id="videoShowcaseTitle" class="mt-1 text-xl font-black tracking-tight sm:text-3xl">Designed to move with you.</h2></div>
                <div class="flex shrink-0 gap-2">
                    <button id="videoPlayToggle" type="button" class="grid h-10 w-10 place-items-center rounded-full border border-amber-200/70 bg-black/45 text-sm text-amber-100 backdrop-blur transition hover:bg-amber-300 hover:text-stone-950 focus:outline-none focus:ring-2 focus:ring-amber-300" aria-label="Play video" aria-pressed="false" disabled>▶</button>
                    <button id="videoMuteToggle" type="button" class="grid h-10 w-10 place-items-center rounded-full border border-amber-200/70 bg-black/45 text-sm text-amber-100 backdrop-blur transition hover:bg-amber-300 hover:text-stone-950 focus:outline-none focus:ring-2 focus:ring-amber-300" aria-label="Unmute video" aria-pressed="true" disabled>🔇</button>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mx-auto mt-5 grid w-full max-w-[1360px] grid-cols-2 gap-px overflow-hidden rounded-2xl bg-gray-200 md:grid-cols-4" aria-label="Store benefits">
<?php foreach([['🚚','Free Shipping','On qualifying orders'],['🔒','Secure Payment','Protected checkout'],['✓','Premium Quality','Carefully selected'],['◷','24/7 Support','Always here to help']] as $feature): ?><div class="flex items-center gap-3 bg-white px-4 py-5 sm:justify-center"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-orange-50 text-lg text-orange-500"><?php echo $feature[0]; ?></span><div><h3 class="text-sm font-bold text-gray-900"><?php echo $feature[1]; ?></h3><p class="text-xs text-gray-500"><?php echo $feature[2]; ?></p></div></div><?php endforeach; ?>
</section>
<!-- LOGIN MODAL -->
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

<div class="bg-white w-[400px] rounded-xl shadow-lg p-6 relative">

<!-- close -->
<button onclick="closeLogin()" 
class="absolute top-3 right-3 text-gray-500 text-xl">
✕
</button>

<h2 class="text-2xl font-bold mb-6 text-center">
Login
</h2>

<form action="login_process.php" method="POST">
<input type="hidden" name="redirect" value="<?php echo htmlspecialchars((string)($_GET['redirect'] ?? 'index.php'), ENT_QUOTES, 'UTF-8'); ?>">

<?php if (!empty($_SESSION['login_error'])): ?>
<div class="mb-4 text-sm text-red-600">
    <?php echo htmlspecialchars($_SESSION['login_error']); ?>
</div>
<?php unset($_SESSION['login_error']); endif; ?>

<input type="text" name="email"
placeholder="Email or Phone"
class="w-full border px-4 py-2 mb-4 rounded">

<input type="password" name="password"
placeholder="Password"
class="w-full border px-4 py-2 mb-2 rounded">

<button type="button"
onclick="openForgot()"
class="text-right w-full text-sm text-gray-500 mb-4 hover:text-orange-500">
Forgot password?
</button>

<button 
class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600">
LOGIN
</button>
<div class="mt-6 text-center text-gray-500 text-sm">
Or login with
</div>

<div class="flex justify-center gap-4 mt-3">

<!-- Google -->
<a href="shopping/google_login.php" 
class="border px-4 py-2 rounded flex items-center gap-2 hover:bg-gray-100">

<img src="https://img.icons8.com/color/20/google-logo.png">
Google

</a>

</div>
</form>

<p class="text-center text-sm mt-4">
Don't have an account?
<a href="#" onclick="openSignup()" class="text-blue-500">Sign up</a>
</p>

</div>
</div>
<div id="signupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

<div class="bg-white w-[400px] rounded-xl shadow-lg p-6 relative">

<button onclick="closeSignup()" 
class="absolute top-3 right-3 text-gray-500 text-xl">✕</button>

<h2 class="text-2xl font-bold mb-6 text-center">Sign Up</h2>

<form action="shopping/register_process.php" method="POST">

<input type="text" name="username" placeholder="Username"
class="w-full border px-4 py-2 mb-4 rounded">


<input type="email" name="email" placeholder="Email"
class="w-full border px-4 py-2 mb-4 rounded" required>

<input type="password" name="password" placeholder="Password"
class="w-full border px-4 py-2 mb-4 rounded" required>

<button type="submit"
class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600">
SIGN UP
</button>

</form>

<p class="text-center text-sm mt-4">
Already have an account?
<a href="#" onclick="openLogin()" class="text-blue-500">Login</a>
</p>

</div>
</div>
<!-- FORGOT PASSWORD MODAL -->
<div id="forgotModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

<div class="bg-white w-[400px] rounded-xl shadow-lg p-6 relative">

<button onclick="closeForgot()" class="absolute top-3 right-3">✕</button>

<h2 class="text-xl font-bold mb-4 text-center">Reset Password</h2>

<!-- STEP 1: EMAIL -->
<div id="stepEmail">
<form id="otpForm" onsubmit="sendOtp(event)">
<div id="otpMessage" class="text-sm text-red-500 mb-4 hidden"></div>
<input type="email" name="email" placeholder="Enter email"
class="w-full border px-4 py-2 mb-4 rounded" required>

<button type="submit" id="sendOtpBtn"
class="w-full bg-orange-500 text-white py-2 rounded">
SEND OTP
</button>
<div id="otpCountdown" class="text-sm text-gray-500 text-center mt-2 hidden">
กรุณารอ <span id="countdownTime">60</span> วินาที
</div>
</form>
</div>

<!-- STEP 2: OTP -->
<div id="stepOtp" class="hidden">
<div id="otpMessage" class="text-sm text-red-500 mb-4 hidden"></div>
<input type="text" id="otp" placeholder="Enter OTP"
class="w-full border px-4 py-2 mb-4 rounded">

<input type="password" id="new_password" placeholder="New Password"
class="w-full border px-4 py-2 mb-4 rounded">

<button onclick="verifyOtp()"
class="w-full bg-green-500 text-white py-2 rounded">
RESET PASSWORD
</button>
</div>

</div>
</div>
<script>

let currentSlide = 0
const totalSlides = 3

function goSlide(index){

const slider = document.getElementById("slider")

slider.style.transform = "translateX(-" + (index * 100) + "%)"

currentSlide = index

}

// auto slide

setInterval(function(){

currentSlide++

if(currentSlide >= totalSlides){
currentSlide = 0
}

goSlide(currentSlide)

},4000)

function showOtpMessage(message, isError = true) {
    const otpMessageElements = document.querySelectorAll('#otpMessage');
    otpMessageElements.forEach(el => {
        el.textContent = message;
        el.classList.remove('hidden');
        el.classList.toggle('text-red-500', isError);
        el.classList.toggle('text-green-500', !isError);
    });
}

function sendOtp(event) {
    event.preventDefault();
    const form = document.getElementById('otpForm');
    const email = form.elements['email'].value.trim();
    const sendBtn = document.getElementById('sendOtpBtn');

    if (!email) {
        showOtpMessage('กรุณากรอกอีเมล', true);
        return;
    }

    // ปิดปุ่มส่ง OTP
    sendBtn.disabled = true;
    sendBtn.classList.add('opacity-50', 'cursor-not-allowed');

    fetch('shopping/send_otp.php', {
        method: 'POST',
        headers: {'Accept': 'application/json'},
        body: new URLSearchParams({email}),
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            showOtpMessage(data.message, false);
            document.getElementById('stepEmail').classList.add('hidden');
            document.getElementById('stepOtp').classList.remove('hidden');
            
            // เริ่ม countdown 60 วินาที
            startOtpCountdown(60);
        } else {
            showOtpMessage(data.message, true);
            // เปิดปุ่มอีกครั้งถ้าเกิดข้อผิดพลาด
            sendBtn.disabled = false;
            sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    })
    .catch(() => {
        showOtpMessage('เกิดข้อผิดพลาดขณะส่ง OTP', true);
        sendBtn.disabled = false;
        sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

function startOtpCountdown(seconds) {
    const countdownDiv = document.getElementById('otpCountdown');
    const countdownTime = document.getElementById('countdownTime');
    const sendBtn = document.getElementById('sendOtpBtn');
    
    countdownDiv.classList.remove('hidden');
    let remaining = seconds;
    
    const interval = setInterval(() => {
        remaining--;
        countdownTime.textContent = remaining;
        
        if (remaining <= 0) {
            clearInterval(interval);
            countdownDiv.classList.add('hidden');
            sendBtn.disabled = false;
            sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }, 1000);
}

function verifyOtp() {
    const otp = document.getElementById('otp').value.trim();
    const newPassword = document.getElementById('new_password').value.trim();

    if (!otp || !newPassword) {
        showOtpMessage('กรุณากรอก OTP และรหัสผ่านใหม่ให้ครบ', true);
        return;
    }

    fetch('shopping/verify_otp.php', {
        method: 'POST',
        headers: {'Accept': 'application/json'},
        body: new URLSearchParams({otp: otp, new_password: newPassword})
    })
    .then(res => res.json())
    .then(data => {
        showOtpMessage(data.message, data.status !== 'error');
        if (data.status === 'success') {
            setTimeout(() => {
                closeForgot();
                document.getElementById('stepEmail').classList.remove('hidden');
                document.getElementById('stepOtp').classList.add('hidden');
                // เปิดปุ่มส่ง OTP ใหม่
                const sendBtn = document.getElementById('sendOtpBtn');
                sendBtn.disabled = false;
                sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }, 1500);
        }
    })
    .catch(() => {
        showOtpMessage('เกิดข้อผิดพลาด ไม่สามารถรีเซ็ทได้', true);
    });
}

</script>



<!-- TRENDING PRODUCTS -->

<section id="trending-products" class="mt-12 w-full px-4 py-10 sm:px-6 lg:px-10">

<div class="mx-auto mb-7 flex max-w-[1440px] items-end justify-between gap-4">
<div><p class="text-[11px] font-bold tracking-[.28em] text-[#a87b24]">THE EDIT</p><h2 class="mt-1 text-2xl font-black tracking-tight text-stone-950 sm:text-3xl">Trending Products</h2></div>
<p class="hidden max-w-xs text-right text-sm text-stone-500 sm:block">A considered selection of the pairs everyone is reaching for.</p>
</div>

<div class="mx-auto grid max-w-[1440px] grid-cols-2 gap-3 sm:gap-5 md:grid-cols-3 lg:grid-cols-4">

<?php

$sql = "SELECT * FROM products ORDER BY id ASC LIMIT 12";
$result = mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($result)){

?>
<a href="shopping/product_detail.php?id=<?php echo (int) $row['id']; ?>" class="luxury-product-card group flex h-full flex-col p-2 sm:p-3">
<div class="luxury-product-image relative aspect-square overflow-hidden rounded-xl">
<span class="absolute left-3 top-3 z-[2] rounded-full border border-[#b58a32]/30 bg-white/85 px-2.5 py-1 text-[9px] font-bold tracking-[.16em] text-[#86601d] backdrop-blur">CURATED</span>
<img src="assets/images/<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>" class="h-full w-full object-contain p-3 sm:p-5">
</div>

<div class="relative z-[2] flex flex-1 flex-col px-1 pb-2 pt-4 sm:px-2">
<h3 class="min-h-10 text-sm font-bold leading-snug text-stone-900 sm:text-[15px]">
<?php echo $row['name']; ?>
</h3>

<p class="mt-auto pt-3 text-base font-black tracking-tight text-[#9b701c] sm:text-lg">
$<?php echo number_format((float) $row['price'], 2); ?>
</p>

<div class="mt-1 text-xs tracking-[.08em] text-[#c59628]">
★★★★★ <span class="ml-1 tracking-normal text-stone-500"><?php echo number_format((float) ($row['rating'] ?? 0), 1); ?></span>
</div>

<?php if (!empty($row['sold_count'])) : ?>
<p class="mt-2 text-[11px] font-medium text-stone-500"><?php echo (int) $row['sold_count']; ?> sold</p>
<?php endif; ?>

</div>
</a>
<?php } ?>

</div>

</section>


<!-- FOOTER -->
<?php include "includes/footer.php"; ?>
<?php if (isset($_GET['login'])): ?><script>document.addEventListener('DOMContentLoaded',()=>{alert('Please login to continue shopping.');openLogin()});</script><?php endif; ?>
<script>
(function(){const carousel=document.getElementById('heroCarousel');if(!carousel)return;const slides=[...carousel.querySelectorAll('.hero-slide')],dots=[...carousel.querySelectorAll('.hero-dot')];let current=0,timer,startX=0;function show(next){current=(next+slides.length)%slides.length;slides.forEach((slide,i)=>{const active=i===current;slide.classList.toggle('is-active',active);slide.setAttribute('aria-hidden',String(!active))});dots.forEach((dot,i)=>dot.classList.toggle('is-active',i===current))}function play(){clearInterval(timer);if(!matchMedia('(prefers-reduced-motion: reduce)').matches)timer=setInterval(()=>show(current+1),4500)}carousel.querySelector('#heroPrev').addEventListener('click',()=>{show(current-1);play()});carousel.querySelector('#heroNext').addEventListener('click',()=>{show(current+1);play()});dots.forEach(dot=>dot.addEventListener('click',()=>{show(Number(dot.dataset.slideTo));play()}));carousel.addEventListener('mouseenter',()=>clearInterval(timer));carousel.addEventListener('mouseleave',play);carousel.addEventListener('focusin',()=>clearInterval(timer));carousel.addEventListener('focusout',play);carousel.addEventListener('touchstart',e=>startX=e.changedTouches[0].clientX,{passive:true});carousel.addEventListener('touchend',e=>{const distance=e.changedTouches[0].clientX-startX;if(Math.abs(distance)>45){show(current+(distance<0?1:-1));play()}},{passive:true});carousel.addEventListener('keydown',e=>{if(e.key==='ArrowLeft')show(current-1);if(e.key==='ArrowRight')show(current+1)});play()})();
</script>
<script>
(function(){const video=document.getElementById('showcaseVideo'),fallback=document.getElementById('videoFallback'),playButton=document.getElementById('videoPlayToggle'),muteButton=document.getElementById('videoMuteToggle');if(!video)return;const setState=()=>{const paused=video.paused;playButton.textContent=paused?'▶':'Ⅱ';playButton.setAttribute('aria-label',paused?'Play video':'Pause video');playButton.setAttribute('aria-pressed',String(!paused));muteButton.textContent=video.muted?'🔇':'🔊';muteButton.setAttribute('aria-label',video.muted?'Unmute video':'Mute video');muteButton.setAttribute('aria-pressed',String(video.muted))};const showFallback=()=>{video.classList.add('hidden');fallback.classList.remove('hidden');playButton.disabled=true;muteButton.disabled=true};playButton.disabled=false;muteButton.disabled=false;video.addEventListener('play',setState);video.addEventListener('pause',setState);video.addEventListener('volumechange',setState);video.addEventListener('error',showFallback);playButton.addEventListener('click',()=>{if(video.paused){video.play().catch(()=>setState())}else video.pause()});muteButton.addEventListener('click',()=>{video.muted=!video.muted;setState()});video.play().catch(()=>setState());setState()})();
</script>
<script>
function openLogin(){
    // ปิด signup ก่อน
    document.getElementById("signupModal").classList.remove("flex")
    document.getElementById("signupModal").classList.add("hidden")

    // เปิด login
    document.getElementById("loginModal").classList.remove("hidden")
    document.getElementById("loginModal").classList.add("flex")
}

function openSignup(){
    // ปิด login ก่อน
    document.getElementById("loginModal").classList.remove("flex")
    document.getElementById("loginModal").classList.add("hidden")

    // เปิด signup
    document.getElementById("signupModal").classList.remove("hidden")
    document.getElementById("signupModal").classList.add("flex")
}

function openForgot(){
    document.getElementById("loginModal").classList.remove("flex")
    document.getElementById("loginModal").classList.add("hidden")
    document.getElementById("signupModal").classList.remove("flex")
    document.getElementById("signupModal").classList.add("hidden")

    document.getElementById("forgotModal").classList.remove("hidden")
    document.getElementById("forgotModal").classList.add("flex")

    document.getElementById('stepEmail').classList.remove('hidden');
    document.getElementById('stepOtp').classList.add('hidden');
    document.querySelectorAll('#otpMessage').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    
    // เปิดปุ่มส่ง OTP ใหม่
    const sendBtn = document.getElementById('sendOtpBtn');
    sendBtn.disabled = false;
    sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    document.getElementById('otpCountdown').classList.add('hidden');
}

function closeForgot(){
    document.getElementById("forgotModal").classList.remove("flex")
    document.getElementById("forgotModal").classList.add("hidden")
    // Clear form fields
    document.getElementById('otpForm').reset();
    document.getElementById('otp').value = '';
    document.getElementById('new_password').value = '';
    document.querySelectorAll('#otpMessage').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    // เปิดปุ่มส่ง OTP และปิด countdown
    const sendBtn = document.getElementById('sendOtpBtn');
    sendBtn.disabled = false;
    sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    document.getElementById('otpCountdown').classList.add('hidden');
}
</script>
<script>
window.onclick = function(e){

    const loginModal = document.getElementById("loginModal")
    const signupModal = document.getElementById("signupModal")
    const forgotModal = document.getElementById("forgotModal")

    if(e.target.id === "loginModal"){
        closeLogin()
    }

    if(e.target.id === "signupModal"){
        closeSignup()
    }

    if(e.target.id === "forgotModal"){
        closeForgot()
    }
}
</script>
<script>
function closeLogin(){
    document.getElementById("loginModal").classList.remove("flex")
    document.getElementById("loginModal").classList.add("hidden")
    // Clear form fields
    const loginForm = document.querySelector('#loginModal form');
    if (loginForm) loginForm.reset();
}

function closeSignup(){
    document.getElementById("signupModal").classList.remove("flex")
    document.getElementById("signupModal").classList.add("hidden")
    // Clear form fields
    const signupForm = document.querySelector('#signupModal form');
    if (signupForm) signupForm.reset();
}
</script>
</script>

</body>
</html>
