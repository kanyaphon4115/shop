<?php session_start(); ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Shipping | SPARK</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 text-gray-900">
<header class="bg-white shadow"><div class="flex items-center justify-between px-5 py-4 md:px-10"><a href="../index.php" class="text-3xl font-bold">SPARK</a><span class="text-sm text-gray-500">Secure checkout</span></div></header>
<nav class="overflow-x-auto border-t bg-white"><div class="min-w-[720px]"><?php include __DIR__ . '/../includes/navbar.php'; ?></div></nav>
<main class="mx-auto max-w-4xl px-4 py-10"><section class="rounded bg-white p-5 shadow-sm md:p-8">
<div class="mb-10 flex items-center justify-center text-center text-xs md:text-sm"><a href="cart.php" class="text-gray-500"><b class="mx-auto block h-8 w-8 rounded-full border leading-7">1</b>Shopping Cart</a><i class="mx-3 h-px max-w-48 flex-1 bg-gray-300"></i><div class="text-orange-600"><b class="mx-auto block h-8 w-8 rounded-full bg-orange-500 leading-8 text-white">2</b><strong>Shipping</strong></div><i class="mx-3 h-px max-w-48 flex-1 bg-gray-300"></i><div class="text-gray-500"><b class="mx-auto block h-8 w-8 rounded-full border leading-7">3</b>Payment</div></div>
<h1 class="text-2xl font-bold">Shipping information</h1><p class="mt-2 text-sm text-gray-500">Enter the address where you would like your order delivered.</p>
<form id="shippingForm" class="mt-7 grid gap-5 sm:grid-cols-2">
<label><span class="mb-2 block text-sm font-medium">Full name</span><input name="name" required autocomplete="name" class="w-full rounded border px-4 py-3 focus:border-orange-500 focus:outline-none"></label>
<label><span class="mb-2 block text-sm font-medium">Email</span><input name="email" type="email" required autocomplete="email" class="w-full rounded border px-4 py-3 focus:border-orange-500 focus:outline-none"></label>
<label><span class="mb-2 block text-sm font-medium">Phone number</span><input name="phone" required autocomplete="tel" pattern="[0-9+() -]{8,20}" class="w-full rounded border px-4 py-3 focus:border-orange-500 focus:outline-none"></label>
<label><span class="mb-2 block text-sm font-medium">Postal code</span><input name="postal_code" required autocomplete="postal-code" pattern="[A-Za-z0-9 -]{3,12}" class="w-full rounded border px-4 py-3 focus:border-orange-500 focus:outline-none"></label>
<label class="sm:col-span-2"><span class="mb-2 block text-sm font-medium">Street address</span><textarea name="address" required autocomplete="street-address" rows="3" class="w-full rounded border px-4 py-3 focus:border-orange-500 focus:outline-none"></textarea></label>
<label><span class="mb-2 block text-sm font-medium">City / District</span><input name="city" required autocomplete="address-level2" class="w-full rounded border px-4 py-3 focus:border-orange-500 focus:outline-none"></label>
<label><span class="mb-2 block text-sm font-medium">Province</span><input name="province" required autocomplete="address-level1" class="w-full rounded border px-4 py-3 focus:border-orange-500 focus:outline-none"></label>
<div class="mt-3 flex flex-col-reverse gap-3 sm:col-span-2 sm:flex-row sm:justify-between"><a href="cart.php" class="rounded border px-6 py-3 text-center hover:border-orange-500">← Back to Shopping Cart</a><button type="submit" class="rounded bg-orange-500 px-7 py-3 font-semibold text-white hover:bg-orange-600">Continue to Payment →</button></div>
</form></section></main>
<script>
const cart = JSON.parse(localStorage.getItem('cart') || '[]');
if (!Array.isArray(cart) || !cart.length) location.href = 'cart.php';
const form = document.querySelector('#shippingForm');
try { const saved = JSON.parse(localStorage.getItem('shippingInfo') || '{}'); Object.entries(saved).forEach(([key,value])=>{if(form.elements[key])form.elements[key].value=value}); } catch(error) { localStorage.removeItem('shippingInfo'); }
form.addEventListener('submit', event => { event.preventDefault(); if (!form.reportValidity()) return; const shipping = Object.fromEntries(new FormData(form).entries()); localStorage.setItem('shippingInfo', JSON.stringify(shipping)); location.href = 'payment.php'; });
</script></body></html>
