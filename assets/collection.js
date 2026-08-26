const cards=[...document.querySelectorAll('.product-card')],grid=document.querySelector('#grid'),perPage=8;
let page=1,category='all'; const $=s=>document.querySelector(s);
function categoryMatch(c){
 if(category==='all'||category==='sneakers'||category==='boys'||category==='girls')return true;
 if(category==='sale')return c.dataset.sale==='1';
 if(category==='new arrivals')return +c.dataset.created>Date.now()/1000-7776000;
 if(category==='best sellers')return +c.dataset.sold>=100;
 if(category==='running')return /air max|runner|running/.test(c.dataset.name);
 if(category==='basketball'||category==='sports')return /jordan|dunk|basketball/.test(c.dataset.name);
 if(category==='lifestyle')return /samba|force|suede|converse|550/.test(c.dataset.name);
 if(category==='school shoes')return /superstar|converse|suede/.test(c.dataset.name);
 return true;
}
function apply(){
 const brand=$('#brand').value,max=+$('#price').value,rating=+$('#rating').value,size=$('#size').value;
 let items=cards.filter(c=>categoryMatch(c)&&(!brand||c.dataset.brand===brand)&&+c.dataset.price<=max&&+c.dataset.rating>=rating&&(!size||c.dataset.sizes.includes('"'+size+'"')));
 const sort=$('#sort').value;
 items.sort((a,b)=>sort==='low'?+a.dataset.price-+b.dataset.price:sort==='high'?+b.dataset.price-+a.dataset.price:sort==='newest'?+b.dataset.created-+a.dataset.created:sort==='rating'?+b.dataset.rating-+a.dataset.rating:+b.dataset.sold-+a.dataset.sold);
 cards.forEach(c=>c.hidden=true); items.forEach(c=>grid.append(c));
 const pages=Math.ceil(items.length/perPage); page=Math.min(page,Math.max(1,pages));
 items.forEach((c,i)=>c.hidden=!(i>=(page-1)*perPage&&i<page*perPage));
 $('#resultCount').textContent=items.length; $('#empty').classList.toggle('hidden',items.length!==0);
 $('#pagination').innerHTML=pages>1?Array.from({length:pages},(_,i)=>'<button class="h-10 w-10 rounded '+(i+1===page?'bg-orange-500 text-white':'border bg-white')+'" data-page="'+(i+1)+'">'+(i+1)+'</button>').join(''):'';
}
document.querySelectorAll('#brand,#price,#size,#rating,#sort').forEach(el=>el.addEventListener('input',()=>{page=1;$('#priceLabel').textContent='$'+Number($('#price').value).toLocaleString();apply()}));
$('#categories').addEventListener('click',e=>{const b=e.target.closest('.category-btn');if(!b)return;document.querySelectorAll('.category-btn').forEach(x=>x.className='category-btn shrink-0 rounded-full border bg-white px-5 py-2 text-sm font-semibold');b.className+=' border-orange-500 bg-orange-500 text-white';category=b.dataset.category;page=1;apply()});
$('#pagination').addEventListener('click',e=>{if(e.target.dataset.page){page=+e.target.dataset.page;apply();scrollTo({top:grid.offsetTop-120,behavior:'smooth'})}});
$('#filterToggle').onclick=()=>$('#filters').classList.toggle('filter-open');
$('#clear').onclick=()=>{$('#brand').value='';$('#price').value=5000;$('#size').value='';$('#rating').value=0;$('#priceLabel').textContent='$5,000';page=1;apply()}; apply();
