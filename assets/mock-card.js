// UI demonstration only. Never transmit or persist these values.
document.querySelectorAll('[data-mock-card]').forEach(input => {
 input.addEventListener('input', () => {
  const digits=input.value.replace(/\D/g,'');
  if(input.dataset.mockCard==='number') input.value=digits.slice(0,19).replace(/(.{4})(?=.)/g,'$1 ');
  else if(input.dataset.mockCard==='expiry') input.value=digits.slice(0,4).replace(/^(\d{2})(\d)/,'$1 / $2');
  else input.value=digits.slice(0,4);
 });
});
