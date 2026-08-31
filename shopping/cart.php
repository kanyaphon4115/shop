<?php
session_start();
require_once __DIR__ . '/../config/connection.php';
$purchaseLoggedIn = !empty($_SESSION['user_id']);
$recommended = mysqli_query($conn, 'SELECT id, name, price, image FROM products ORDER BY created_at DESC LIMIT 6');
function e($value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Shopping Cart | SPARK</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{color:#222}.cart-check{accent-color:#f4511e}.step-line{height:1px;background:#d1d5db;flex:1}.qty-btn{width:38px;height:38px;border:1px solid #e5e7eb;font-size:20px}.cart-row{display:grid;grid-template-columns:28px 100px minmax(180px,1fr) 115px 145px 125px;gap:16px;align-items:center}@media(max-width:850px){.header-search{display:none}.cart-row{grid-template-columns:28px 82px 1fr;align-items:start}.cart-price,.cart-qty,.cart-subtotal{grid-column:3}.cart-actions{flex-wrap:wrap}.nav-wrap{overflow-x:auto}.nav-wrap>div{min-width:720px;padding-left:1rem;padding-right:1rem}}
</style>
</head>
<body class="bg-gray-100">
<header class="bg-white shadow-sm"><div class="flex items-center justify-between px-5 py-4 md:px-10"><a href="../index.php" class="text-3xl font-bold">SPARK</a><form action="search.php" method="get" class="header-search flex w-1/2"><select name="category" class="rounded-l border px-3" aria-label="Product category"><option value="all">All Categories</option><option value="men">Men</option><option value="women">Women</option><option value="kids">Kids</option><option value="sneakers">Sneakers</option><option value="sale">Sale</option></select><input name="q" class="flex-1 border px-4 py-2" placeholder="Search for more than 20,000 products"><button type="submit" class="rounded-r bg-orange-500 px-6 text-white" aria-label="Search">⌕</button></form></div></header>
<nav class="nav-wrap border-t bg-white"><?php include __DIR__ . '/../includes/navbar.php'; ?></nav>

<main class="mx-auto max-w-[1500px] px-4 py-6">
<div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_370px]">
<section class="min-w-0 rounded bg-white p-4 shadow-sm md:p-6">
<h1 class="text-2xl font-bold">Shopping Cart</h1>
<div class="my-7 flex items-center justify-center text-center text-xs md:text-sm"><div class="text-orange-600"><b class="mx-auto block h-8 w-8 rounded-full bg-orange-500 leading-8 text-white">1</b><strong>Shopping Cart</strong></div><i class="step-line mx-3 max-w-52"></i><div class="text-gray-500"><b class="mx-auto block h-8 w-8 rounded-full border bg-white leading-7">2</b>Shipping</div><i class="step-line mx-3 max-w-52"></i><div class="text-gray-500"><b class="mx-auto block h-8 w-8 rounded-full border bg-white leading-7">3</b>Payment</div></div>

<div class="flex items-center gap-4 border-y py-4 text-sm"><label class="flex items-center gap-2"><input id="selectAll" class="cart-check h-5 w-5" type="checkbox" checked> Select All (<span id="cartKinds">0</span>)</label><button id="deleteSelected" class="text-gray-600 hover:text-orange-600">Delete selected</button></div>
<div id="cartList"></div>
<div class="mt-6 flex flex-col gap-3 border-t pt-5 sm:flex-row"><input class="min-w-0 flex-1 rounded border px-4 py-3" placeholder="Enter voucher code" aria-label="Voucher code"><button id="voucherButton" class="rounded border px-8 py-3 hover:border-orange-500">Apply</button></div><p id="voucherNote" class="mt-2 text-xs text-gray-500">Voucher UI only — no discount system is connected yet.</p>
</section>

<aside class="h-fit rounded bg-white p-6 shadow-sm lg:sticky lg:top-5"><h2 class="text-xl font-bold">Order Summary</h2><dl class="mt-6 space-y-5"><div class="flex justify-between"><dt>Subtotal (<span id="selectedCount">0</span> items)</dt><dd>$<span id="subtotal">0.00</span></dd></div><div class="flex justify-between"><dt>Shipping Fee</dt><dd class="text-gray-500">Calculated at next step</dd></div><div class="flex justify-between"><dt>Discount</dt><dd class="text-green-600">− $0.00</dd></div><div class="flex justify-between border-t pt-5 text-xl"><dt>Total</dt><dd class="font-bold text-orange-600">$<span id="total">0.00</span></dd></div></dl><button id="checkout" class="mt-6 w-full rounded bg-orange-500 py-3 font-semibold text-white hover:bg-orange-600">Checkout (<span id="checkoutCount">0</span>)</button><a href="../index.php" class="mt-3 block w-full rounded border py-3 text-center hover:border-orange-500">Continue Shopping</a><div class="mt-6 space-y-3 border-t pt-5 text-sm"><p>✓ 100% Authentic</p><p>↻ 7-Day Returns</p><p>♢ Secure checkout</p></div></aside>
</div>

<section class="mt-8"><h2 class="mb-4 text-xl font-bold">You May Also Like</h2><div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6"><?php while ($product = mysqli_fetch_assoc($recommended)): ?><a href="product_detail.php?id=<?php echo (int)$product['id']; ?>" class="rounded bg-white p-3 shadow-sm transition hover:-translate-y-1 hover:shadow-md"><img class="h-36 w-full object-contain" src="../assets/images/<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>"><h3 class="mt-3 min-h-10 text-sm font-semibold"><?php echo e($product['name']); ?></h3><p class="mt-2 font-bold text-orange-600">$<?php echo number_format((float)$product['price'], 2); ?></p></a><?php endwhile; ?></div></section>
</main>
<script>
const CART_KEY = 'cart';
const money = n => Number(n).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});
const escapeHtml = value => String(value).replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
let cart = loadCart();
function loadCart(){try{const value=JSON.parse(localStorage.getItem(CART_KEY)||'[]');return Array.isArray(value)?value:[]}catch(error){return[]}}
function saveCart(){localStorage.setItem(CART_KEY,JSON.stringify(cart));updateBadge()}
function updateBadge(){const count=cart.reduce((sum,item)=>sum+Math.max(1,Number(item.quantity)||1),0);const badge=document.querySelector('#cartBadge');if(badge){badge.textContent=count;badge.classList.toggle('hidden',count===0)}}
function itemKey(item){return `${Number(item.id)}:${item.size}`}
const rows = () => [...document.querySelectorAll('.cart-row')];
function render(){const list=document.querySelector('#cartList');if(!cart.length){list.innerHTML='<div id="emptyCart" class="py-16 text-center"><div class="text-5xl">🛒</div><h2 class="mt-4 text-xl font-semibold">Your cart is empty</h2><a href="../index.php" class="mt-5 inline-block rounded bg-orange-500 px-6 py-3 text-white">Start shopping</a></div>'}else{list.innerHTML=cart.map(item=>{item.quantity=Math.max(1,Number(item.quantity)||1);return `<article class="cart-row border-b py-5" data-key="${escapeHtml(itemKey(item))}" data-price="${Number(item.price)}"><input class="item-check cart-check h-5 w-5" type="checkbox" checked aria-label="Select ${escapeHtml(item.name)}"><a href="product_detail.php?id=${Number(item.id)}"><img class="h-24 w-24 rounded bg-gray-50 object-contain" src="../assets/images/${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}"></a><div><a class="font-semibold hover:text-orange-600" href="product_detail.php?id=${Number(item.id)}">${escapeHtml(item.name)}</a><p class="mt-2 text-sm text-gray-500">Variation: ${escapeHtml(item.size)}</p><div class="cart-actions mt-3 flex gap-4 text-sm"><button class="remove text-blue-700">Delete</button><button class="save text-blue-700">Save for later</button></div></div><div class="cart-price text-orange-600">$${money(item.price)}</div><div class="cart-qty flex"><button class="qty-btn minus" aria-label="Decrease quantity">−</button><input class="quantity h-[38px] w-12 border-y text-center" value="${item.quantity}" readonly><button class="qty-btn plus" aria-label="Increase quantity">+</button></div><strong class="cart-subtotal text-orange-600">$<span class="line-total">${money(Number(item.price)*item.quantity)}</span></strong></article>`}).join('')};document.querySelector('#cartKinds').textContent=cart.length;saveCart();recalc()}
function recalc(){let sum=0,count=0; rows().forEach(row=>{const q=+row.querySelector('.quantity').value,line=+row.dataset.price*q;row.querySelector('.line-total').textContent=money(line);if(row.querySelector('.item-check').checked){sum+=line;count+=q}});document.querySelector('#subtotal').textContent=money(sum);document.querySelector('#total').textContent=money(sum);document.querySelector('#selectedCount').textContent=count;document.querySelector('#checkoutCount').textContent=count;document.querySelector('#selectAll').checked=rows().length>0&&rows().every(r=>r.querySelector('.item-check').checked)}
document.querySelector('#cartList').addEventListener('click',e=>{const row=e.target.closest('.cart-row');if(!row)return;const index=cart.findIndex(item=>itemKey(item)===row.dataset.key);if(index<0)return;if(e.target.closest('.plus'))cart[index].quantity=Math.max(1,Number(cart[index].quantity)||1)+1;if(e.target.closest('.minus'))cart[index].quantity=Math.max(1,(Number(cart[index].quantity)||1)-1);if(e.target.closest('.remove')||e.target.closest('.save'))cart.splice(index,1);saveCart();render()});
document.querySelector('#cartList').addEventListener('change',e=>{if(e.target.matches('.item-check'))recalc()});
document.querySelector('#selectAll').addEventListener('change',e=>{rows().forEach(r=>r.querySelector('.item-check').checked=e.target.checked);recalc()});
document.querySelector('#deleteSelected').addEventListener('click',()=>{const selected=new Set(rows().filter(row=>row.querySelector('.item-check').checked).map(row=>row.dataset.key));cart=cart.filter(item=>!selected.has(itemKey(item)));saveCart();render()});
document.querySelector('#voucherButton').addEventListener('click',()=>document.querySelector('#voucherNote').textContent='Voucher codes are not active yet; no discount was applied.');
document.querySelector('#checkout').addEventListener('click',()=>{if(!<?php echo $purchaseLoggedIn?'true':'false'; ?>){alert('Please login to continue shopping.');location.href='../index.php?login=1&redirect='+encodeURIComponent(window.location.pathname+window.location.search);return}if(+document.querySelector('#selectedCount').textContent<1){alert('Please select at least one product.');return}location.href='checkout.php'});
render();
</script>
</body></html>
