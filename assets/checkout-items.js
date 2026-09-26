// Buy Now travels with the URL, independently of the shopping cart.
window.sparkCheckoutItems = () => {
 const params=new URLSearchParams(location.search);
 if(params.has('id')){
  const id=Number(params.get('id')),size=(params.get('size')||'').trim();
  if(!Number.isInteger(id)||id<1||!size)throw Error('Please return to the product and select a size.');
  return [{id,size,quantity:1}];
 }
 const items=JSON.parse(localStorage.getItem('cart')||'[]');
 return Array.isArray(items)?items:[];
};
