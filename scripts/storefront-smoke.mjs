import assert from 'node:assert/strict';
import {execFileSync} from 'node:child_process';
function fetch(url,options={}){
 const args=['--noproxy','*','-sS','-L','-w','\n__STATUS__%{http_code}',url];
 if(options.method)args.push('-X',options.method);
 for(const [key,value] of Object.entries(options.headers||{}))args.push('-H',key+': '+value);
 if(options.body)args.push('--data',options.body);
 const result=execFileSync('curl.exe',args,{encoding:'utf8',maxBuffer:8000000});
 const offset=result.lastIndexOf('\n__STATUS__');
 return {status:Number(result.slice(offset+11)),text:()=>result.slice(0,offset)};
}
const origin=process.env.STOREFRONT_URL||'http://127.0.0.1:8000';
const cases=[
['/','Best Sellers'],['/about','About Us'],['/contact','Contact Us'],
['/products','Ampus Cosmo'],['/products?search=zzzz-no-watch','No products found'],
['/products?sort=price_low','Watches'],['/products?availability[]=out_of_stock','Sold Out'],
['/product-category/mens','Mens'],['/product-category/womens','Womens'],['/product-categories','Product Categories'],
['/product/ampus-cosmo-de-milancelos-scelerisque','data-variant-form'],
['/product/diverra-dulla-minterdum-andacony-maliduet','Out of Stock'],
['/blogs','Dinterdum'],['/blogs?q=zzzz-no-article','No articles found'],
['/category','Blog Categories'],['/category/luxury-watches','Luxury Watches'],
['/blog/loremous-comodous-trending','Sample Unordered List'],
['/cart','Your cart is empty'],['/faqs','How do I place an order'],['/shipping-and-returns','Shipping'],
['/brands','Brands'],['/lookbook','Lookbook'],['/image-gallery','Image Gallery'],
['/login','WristWatch'],['/register','WristWatch'],['/about-us','About Us'],['/contact-us','Contact Us'],['/blog','Blog'],
];
let passed=0;
for(const [path,expected] of cases){
 const response=await fetch(origin+path);const html=await response.text();
 assert.equal(response.status,200,`${path}: HTTP ${response.status}`);
 assert.ok(html.includes(expected),`${path}: missing ${expected}`);
 assert.ok(!html.includes('Internal Server Error'),`${path}: server exception`);
 if(!path.startsWith('/login')&&!path.startsWith('/register'))assert.ok(!html.includes('>Arizona Outfits<'),`${path}: old public branding`);
 if(path==='/'){
  const sources=[...html.matchAll(/(?:src|href)="([^" ]*\/storefront\/[^" ]+)"/g)].map(m=>m[1]);
  for(const url of new Set(sources)){const asset=await fetch(url);assert.equal(asset.status,200,`Missing asset: ${url}`);}
 }
 passed++;console.log(`PASS ${path}`);
}
const invalid=await fetch(origin+'/newsletter/subscribe',{method:'POST',headers:{Accept:'application/json','Content-Type':'application/x-www-form-urlencoded'},body:'email=invalid'});
assert.equal(invalid.status,419,'Newsletter POST must reject missing CSRF token');
console.log(`PASS CSRF protection\n${passed+1} checks passed.`);

