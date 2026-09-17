(() => {
 'use strict';
 const cfg=window.sparkPayment, button=document.getElementById('placeOrder'), label=document.getElementById('payLabel'), error=document.getElementById('paymentError');
 let cart=[],shipping={},total=0,stripe,elements,ready=false,busy=false,requestKey,order;
 const esc=v=>String(v??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
 const money=v=>new Intl.NumberFormat('en-US',{style:'currency',currency:cfg.currency.toUpperCase(),maximumFractionDigits:2}).format(v);
 const method=()=>document.querySelector('[name=payment_method]:checked')?.value;
 async function api(url,data){const response=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...data,csrf:cfg.csrf})});const result=await response.json();if(!response.ok||result.ok===false||result.error)throw Error(result.error||result.message||'Please try again.');return result;}
 function updateButton(){button.disabled=busy||!ready||(method()==='card'&&!elements);label.textContent=busy?'Processing…':method()==='cod'?'Place order · '+money(total):'Pay '+money(total);}
 function finish(number){const current=JSON.parse(localStorage.getItem('cart')||'[]');const ordered=new Map(cart.map(i=>[`${Number(i.id)}:${i.size}`,Number(i.quantity)||1]));localStorage.setItem('cart',JSON.stringify(current.flatMap(i=>{const n=ordered.get(`${Number(i.id)}:${i.size}`);if(!n)return[i];const remaining=(Number(i.quantity)||1)-n;return remaining>0?[{...i,quantity:remaining}]:[];})));sessionStorage.removeItem('sparkCheckout');location.href='order_success.php?order='+encodeURIComponent(number);}
 async function verify(number){await api('payment_api.php',{action:'verify',order:number});finish(number);}
 async function init(){try{
  const returning=new URLSearchParams(location.search);
  if(returning.has('order')){const previous=JSON.parse(sessionStorage.getItem('sparkCheckout')||'{}');cart=previous.cart||[];try{await verify(returning.get('order'));return;}catch(e){error.textContent=e.message;history.replaceState({},'',location.pathname);}}
  cart=JSON.parse(localStorage.getItem('cart')||'[]');shipping=JSON.parse(localStorage.getItem('shippingInfo')||'{}');
  if(!Array.isArray(cart)||!cart.length){location.href='cart.php';return;}
  if(['name','email','phone','address','city','province','postal_code'].some(k=>!String(shipping[k]||'').trim())){location.href='checkout.php';return;}
  document.getElementById('shippingPreview').innerHTML=`<strong>${esc(shipping.name)}</strong><br>${esc(shipping.address)}<br>${esc(shipping.city)}, ${esc(shipping.province)} ${esc(shipping.postal_code)}<br>${esc(shipping.country||'Thailand')}<br>${esc(shipping.phone)}`;
  document.getElementById('cardholder').value=shipping.name;
  const quote=await api('create_order.php',{action:'quote',items:cart});total=Number(quote.total);
  document.getElementById('summaryItems').innerHTML=quote.items.map(i=>`<article class="summary-item"><img src="../assets/images/${esc(encodeURIComponent(i.image))}" alt="${esc(i.name)}"><div class="item-info"><strong>${esc(i.name)}</strong><p>Size ${esc(i.size)}</p><p>Qty: ${i.quantity}</p><b class="item-price">${money(i.price*i.quantity)}</b></div></article>`).join('');
  document.getElementById('subtotal').textContent=money(total);document.getElementById('finalTotal').textContent=money(total);document.getElementById('shippingTotal').textContent=money(0);document.getElementById('discountTotal').textContent='− '+money(0);
  ready=true;updateButton();
  document.getElementById('cancelPayment').hidden=!JSON.parse(sessionStorage.getItem('sparkCheckout')||'{}').order;
  if(cfg.key){const account=await fetch('account_data.php').then(r=>r.json());const saved=(account.payment_methods||[]).filter(p=>p.provider==='stripe');if(saved.length){document.getElementById('savedCardChoice').hidden=false;document.getElementById('savedCard').insertAdjacentHTML('beforeend',saved.map(p=>`<option value="${Number(p.id)}">${esc(p.brand)} •••• ${esc(p.last4)}${Number(p.is_default)?' · Default':''}</option>`).join(''));}}
  if(cfg.key){stripe=Stripe(cfg.key);elements=stripe.elements({mode:'payment',amount:Math.round(total*100),currency:cfg.currency,paymentMethodTypes:['card'],setupFutureUsage:'off_session',appearance:{theme:'stripe',variables:{colorPrimary:'#a66e12',borderRadius:'5px',fontFamily:'Roboto, Arial, sans-serif'}}});document.getElementById('cardElement').innerHTML='';const card=elements.create('payment',{fields:{billingDetails:{name:'never'}},layout:'tabs'});card.mount('#cardElement');card.on('ready',updateButton);card.on('loaderror',()=>{elements=null;updateButton();error.textContent='Secure card entry could not load. Refresh to try again.';});}
 }catch(e){error.textContent=e.message;}}
 document.querySelectorAll('[name=payment_method]').forEach(r=>r.addEventListener('change',()=>{error.textContent='';updateButton();}));
 document.getElementById('saveCard').addEventListener('change',e=>{if(elements)elements.update({setupFutureUsage:e.target.checked?'off_session':null});});
 document.getElementById('savedCard').addEventListener('change',e=>{document.getElementById('cardElement').hidden=Boolean(e.target.value);document.getElementById('cardholder').disabled=Boolean(e.target.value);document.getElementById('saveCard').disabled=Boolean(e.target.value);});
 document.getElementById('cancelPayment').addEventListener('click',async e=>{if(busy)return;e.target.disabled=true;try{const previous=JSON.parse(sessionStorage.getItem('sparkCheckout')||'{}');await api('payment_api.php',{action:'cancel',order:previous.order});sessionStorage.removeItem('sparkCheckout');location.replace(location.pathname);}catch(err){error.textContent=err.message;e.target.disabled=false;}});
 button.addEventListener('click',async()=>{if(button.disabled||busy)return;const selectedMethod=method();busy=true;document.querySelectorAll('[name=payment_method]:not(:disabled)').forEach(r=>{r.dataset.processing='true';r.disabled=true;});updateButton();error.textContent='';try{
  const savedCard=document.getElementById('savedCard').value;
  if(selectedMethod==='card'&&!savedCard){if(!document.getElementById('cardholder').value.trim())throw Error('Please enter the cardholder name.');const submitted=await elements.submit();if(submitted.error)throw Error(submitted.error.message);}
  const payload={items:cart.map(({id,size,quantity})=>({id,size,quantity})),shipping,payment_method:selectedMethod,saved_card:savedCard,save_card:selectedMethod==='card'&&document.getElementById('saveCard').checked};
  const signature=JSON.stringify(payload), previous=JSON.parse(sessionStorage.getItem('sparkCheckout')||'{}');
  if(previous.signature&&previous.signature!==signature&&previous.order)throw Error('A payment is already pending for this checkout. Restore your previous payment selection or complete that order before starting another.');
  requestKey=previous.signature===signature?previous.key:crypto.randomUUID();sessionStorage.setItem('sparkCheckout',JSON.stringify({signature,key:requestKey,cart}));
  const result=await api('create_order.php',{...payload,request_key:requestKey});order=result.order_number;sessionStorage.setItem('sparkCheckout',JSON.stringify({signature,key:requestKey,cart,order}));
  if(selectedMethod==='cod'){finish(order);return;}
  document.getElementById('cancelPayment').hidden=false;
  if(result.paymentPaid){await verify(order);return;}
  const confirmation=savedCard?await stripe.confirmCardPayment(result.clientSecret):await stripe.confirmPayment({elements,clientSecret:result.clientSecret,confirmParams:{return_url:location.origin+location.pathname+'?order='+encodeURIComponent(order),payment_method_data:{billing_details:{name:document.getElementById('cardholder').value,email:shipping.email}}},redirect:'if_required'});
  if(confirmation.error)throw Error(confirmation.error.message);await verify(order);
 }catch(e){error.textContent=e.message;busy=false;document.querySelectorAll('[data-processing]').forEach(r=>{r.disabled=false;delete r.dataset.processing;});updateButton();}});
 init();
})();
