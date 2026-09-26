(() => {
 const box=document.getElementById('shippingItems'),total=document.getElementById('shippingOrderTotal'),next=document.querySelector('#shippingForm button[type=submit]');
 const esc=v=>String(v??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
 next.disabled=true;
 async function load(){try{
  const items=window.sparkCheckoutItems();
  if(!items.length){location.href='cart.php';return;}
  const response=await fetch('create_order.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'quote',items,csrf:window.sparkShippingCsrf})});
  const quote=await response.json();if(!response.ok||!quote.ok)throw Error(quote.message||'Unable to load products. Please refresh to retry.');
  const money=n=>new Intl.NumberFormat('en-US',{style:'currency',currency:quote.currency.toUpperCase()}).format(n);
  box.innerHTML=quote.items.map(i=>`<article class="summary-item"><img src="../assets/images/${esc(encodeURIComponent(i.image))}" alt="${esc(i.name)}"><div class="item-info"><strong>${esc(i.name)}</strong><p>Size ${esc(i.size)} · Qty: ${i.quantity}</p><b class="item-price">${money(i.price*i.quantity)}</b></div></article>`).join('');
  total.textContent=money(quote.total);next.disabled=false;
 }catch(e){box.textContent=e.message;total.textContent='—';}}
 load();
})();
