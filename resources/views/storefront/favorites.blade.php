@extends('layouts.storefront')
@section('title','Wish Lists | WristWatch')
@section('content')<div class="ww-container"><div class="ww-page-title"><h1>My Wish List</h1></div><div class="ww-products ww-section">@forelse($favorites as $favorite)@include('storefront.product-card',['product'=>$favorite->product])@empty<div class="ww-empty"><p>Your wish list is empty.</p><a class="ww-button" href="{{ route('products.index') }}">Explore Watches</a></div>@endforelse</div><div class="ww-pagination">{{ $favorites->links() }}</div></div>@endsection
