<?php
session_start();
include __DIR__ . "/config/connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Spark Shop</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- HEADER -->

<header class="bg-white shadow">

<div class="w-full px-10 flex items-center justify-between py-4">
<h1 class="text-3xl font-bold">
SPARK
</h1>

<form action="shopping/search.php" method="get" class="flex w-1/2">

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


<nav class="bg-white border-t">

<div class="max-w-7xl mx-auto justify-between items-center py-4">

<?php include "includes/navbar.php"; ?>

</div>

</nav>


<!-- HERO SLIDER -->

<section class="w-full px-10 mt-6">

<div class="relative overflow-hidden rounded">

<!-- images -->

<div id="slider" class="flex transition-transform duration-700 h-[420px] ">

<img src="assets/images/banner1.png"
class="w-full h-full object-contain flex-shrink-0">

<img src="assets/images/benner2.png"
class="w-full h-full object-contain flex-shrink-0">

<img src="assets/images/shoes3.png"
class="w-full h-full object-contain flex-shrink-0">

</div>



</div>

</div>

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


<!-- FEATURES -->

<section class="w-full px-10 mt-12 grid grid-cols-5 gap-6">

<div class="bg-white p-6 rounded shadow text-center">
🚚
<h3 class="font-bold mt-2">Free delivery</h3>
<p class="text-gray-500 text-sm">Lorem ipsum dolor</p>
</div>

<div class="bg-white p-6 rounded shadow text-center">
🔒
<h3 class="font-bold mt-2">100% secure payment</h3>
<p class="text-gray-500 text-sm">Lorem ipsum</p>
</div>

<div class="bg-white p-6 rounded shadow text-center">
🏬
<h3 class="font-bold mt-2">Quality guarantee</h3>
<p class="text-gray-500 text-sm">Lorem ipsum</p>
</div>

<div class="bg-white p-6 rounded shadow text-center">
💰
<h3 class="font-bold mt-2">Guaranteed savings</h3>
<p class="text-gray-500 text-sm">Lorem ipsum</p>
</div>

<div class="bg-white p-6 rounded shadow text-center">
🎁
<h3 class="font-bold mt-2">Daily offers</h3>
<p class="text-gray-500 text-sm">Lorem ipsum</p>
</div>

</section>


<!-- FOOTER -->
<?php include "includes/footer.php"; ?>
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
