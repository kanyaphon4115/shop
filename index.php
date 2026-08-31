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
<script src="https://cdn.tailwindcss.com"></script>
<style>
html,body{overflow-x:hidden}.hero-slide{opacity:0;pointer-events:none;transition:opacity .75s ease}.hero-slide.is-active{opacity:1;pointer-events:auto}.hero-copy>*{opacity:0;transform:translateX(-28px);transition:opacity .65s ease,transform .65s ease}.hero-visual{opacity:0;transform:translateX(38px) scale(.97);transition:opacity .8s ease,transform .8s cubic-bezier(.22,1,.36,1)}.hero-slide.is-active .hero-copy>*{opacity:1;transform:none}.hero-slide.is-active .hero-copy>*:nth-child(2){transition-delay:.08s}.hero-slide.is-active .hero-copy>*:nth-child(3){transition-delay:.16s}.hero-slide.is-active .hero-copy>*:nth-child(4){transition-delay:.24s}.hero-slide.is-active .hero-visual{opacity:1;transform:none}.hero-dot{transition:width .3s ease,background-color .3s ease}.hero-dot.is-active{width:2rem;background:#f97316}@media(prefers-reduced-motion:reduce){.hero-slide,.hero-copy>*,.hero-visual,.hero-dot{transition:none!important}}
</style>
</head>

<body class="bg-gray-100">

<!-- HEADER -->

<header class="bg-white shadow">

<div class="flex w-full flex-col items-center justify-between gap-4 px-4 py-4 sm:px-6 md:flex-row lg:px-10">
<h1 class="text-3xl font-bold">
SPARK
</h1>

<form action="shopping/search.php" method="get" class="flex w-full max-w-2xl md:w-1/2">

<select name="category" class="hidden rounded-l border px-3 py-2 sm:block" aria-label="Product category">
<option value="all">All Categories</option>
<option value="men">Men</option><option value="women">Women</option><option value="kids">Kids</option><option value="sneakers">Sneakers</option><option value="sale">Sale</option>
</select>

<input
name="q"
class="min-w-0 flex-1 rounded-l border px-4 py-2 sm:rounded-none"
placeholder="Search for more than 20,000 products">

<button type="submit" class="bg-blue-500 text-white px-6 rounded-r flex items-center justify-center hover:bg-blue-600 transition" aria-label="Search">
🔍
</button>

</form>


</div>

</header>


<nav class="bg-white border-t">

<div class="max-w-7xl mx-auto justify-between items-center py-4">

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

<section class="w-full px-10 mt-10">

<h2 class="text-2xl font-bold mb-6">
Trending Products
</h2>

<div class="grid grid-cols-2 gap-4 md:grid-cols-3 md:gap-6 lg:grid-cols-4">

<?php

$sql = "SELECT * FROM products ORDER BY id ASC LIMIT 12";
$result = mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($result)){

?>
<a href="shopping/product_detail.php?id=<?php echo $row['id']; ?>">
<div class="flex h-full flex-col rounded bg-white p-4 shadow transition hover:-translate-y-1 hover:shadow-lg">

<img
src="assets/images/<?php echo $row['image']; ?>"
class="aspect-square w-full object-contain">

<h3 class="mt-3 min-h-10 text-sm font-semibold">
<?php echo $row['name']; ?>
</h3>

<p class="mt-auto font-bold text-green-600">
$<?php echo number_format((float) $row['price'], 2); ?>
</p>

<div class="text-yellow-400 text-sm">
★★★★★ <span class="text-gray-500"><?php echo number_format((float) ($row['rating'] ?? 0), 1); ?></span>
</div>

<?php if (!empty($row['sold_count'])) : ?>
<p class="mt-1 text-xs text-gray-500"><?php echo (int) $row['sold_count']; ?> sold</p>
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
