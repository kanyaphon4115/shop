(() => {
 const form=document.getElementById('saveCardForm'), error=document.getElementById('cardError'), button=document.getElementById('saveCard'), cfg=window.sparkPayment;
 document.querySelectorAll('[data-open-card]').forEach(b=>b.onclick=()=>{form.hidden=false;form.scrollIntoView({behavior:'smooth',block:'nearest'});});
 document.querySelector('.pay-close').onclick=()=>{form.hidden=true;document.querySelector('[data-open-card]').focus();};
 let stripe,elements;
 async function api(data){const r=await fetch(cfg.api,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({...data,csrf:cfg.csrf})});const result=await r.json();if(!r.ok||result.error)throw Error(result.error||'Could not save card.');return result;}
 async function init(){if(!cfg.key)return;try{
  const params=new URLSearchParams(location.search);
  if(params.has('setup_intent')){await api({action:'save',intent:params.get('setup_intent'),default:sessionStorage.getItem('sparkDefaultCard')==='true'});location.replace(location.pathname);return;}
  stripe=Stripe(cfg.key);const setup=await api({action:'setup'});elements=stripe.elements({clientSecret:setup.clientSecret,appearance:{theme:'stripe',variables:{colorPrimary:'#a66e12',borderRadius:'5px',fontFamily:'Roboto, Arial, sans-serif'}}});
  document.getElementById('cardElement').innerHTML='';const element=elements.create('payment',{fields:{billingDetails:{name:'never'}},layout:'tabs'});element.mount('#cardElement');element.on('ready',()=>button.disabled=false);element.on('loaderror',()=>{error.textContent='Secure card entry could not load. Please refresh and try again.';});
 }catch(e){error.textContent=e.message;}}
 form.addEventListener('submit',async e=>{e.preventDefault();if(!elements||button.disabled)return;button.disabled=true;button.textContent='Saving…';error.textContent='';try{
  const def=document.getElementById('defaultCard').checked;sessionStorage.setItem('sparkDefaultCard',String(def));
  const result=await stripe.confirmSetup({elements,confirmParams:{return_url:location.origin+location.pathname,payment_method_data:{billing_details:{name:document.getElementById('cardholder').value}}},redirect:'if_required'});
  if(result.error)throw Error(result.error.message);await api({action:'save',intent:result.setupIntent.id,default:def});location.reload();
 }catch(e){error.textContent=e.message;button.disabled=false;button.textContent='Save Card';}});init();
})();
