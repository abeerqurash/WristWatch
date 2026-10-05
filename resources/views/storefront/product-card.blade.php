@php
$media = app(\App\Services\PublicMediaService::class);
$price = $product->sale_price !== null && $product->sale_price < $product->regular_price ? $product->sale_price : $product->regular_price;
$onSale = $price < $product->regular_price;
$selection = $product->variants->isNotEmpty() || $product->options->isNotEmpty() || $product->optionValues->isNotEmpty();
$available = $product->variants->isNotEmpty() ? $product->variants->contains(fn($v)=>app(\App\Services\StoreSettingsService::class)->available((int)$v->stock)>0) : app(\App\Services\StoreSettingsService::class)->available((int)$product->stock)>0;
$hoverImage = $product->images->skip(1)->first();
@endphp
<article class="ww-product">
<div><a class="ww-product-picture" href="{{ route('products.show',$product->slug) }}"><img src="{{ $media->productImage($product) }}" alt="{{ $product->title }}" loading="lazy">@if($hoverImage)<img class="ww-product-hover" src="{{ $media->url($hoverImage->image) }}" alt="{{ $product->title }} alternate view" loading="lazy">@endif</a>
@if($onSale)<span class="ww-product-badge">Sale</span>@endif @if(!$available)<span class="ww-product-badge sold">Sold Out</span>@endif
<div class="ww-product-tools"><button data-quickview="{{ route('products.quick-view',$product->id) }}" aria-label="Quick view {{ $product->title }}">@include('storefront.icon',['icon'=>'search'])</button><form action="{{ route('favorite.toggle') }}" method="post">@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"><button aria-label="Add {{ $product->title }} to wish list">@include('storefront.icon',['icon'=>'heart'])</button></form></div></div>
<div><p class="ww-product-brand">WristWatch</p><h3><a href="{{ route('products.show',$product->slug) }}">{{ $product->title }}</a></h3><div class="ww-product-price">@if($onSale)<del>{{ app(\App\Services\StoreSettingsService::class)->money((float)$product->regular_price) }}</del>@endif<span @class(['sale'=>$onSale])>{{ app(\App\Services\StoreSettingsService::class)->money((float)$price) }}</span></div>
@if(!$available)<a class="ww-button" href="{{ route('products.show',$product->slug) }}">Out of Stock</a>@elseif($selection)<a class="ww-button" href="{{ route('products.show',$product->slug) }}">Choose Options</a>@else<form action="{{ route('cart.add') }}" method="post" data-cart-form>@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"><input type="hidden" name="quantity" value="1"><button class="ww-button">Add to Cart</button></form>@endif</div>
</article>
