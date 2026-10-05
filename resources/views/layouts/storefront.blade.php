<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', ($title ?? 'WristWatch') . ' | WristWatch')</title>
<meta name="description" content="{{ $meta_description ?? 'Discover watches, timeless design and everyday elegance at WristWatch.' }}">
<link rel="canonical" href="{{ url()->current() }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap">
<link rel="stylesheet" href="{{ asset('storefront/storefront.css') }}">
@stack('page-styles')
</head>
<body class="ww-storefront" data-currency-symbol="{{ app(\App\Services\StoreSettingsService::class)->symbol() }}">
<a class="ww-skip" href="#main-content">Skip to content</a>
@php($wwCategories = \App\Models\ProductCategory::whereNull('parent_id')->whereHas('products', fn($q)=>$q->where('status','active'))->orderByRaw("CASE slug WHEN 'new-releases' THEN 0 WHEN 'mens' THEN 1 WHEN 'womens' THEN 2 WHEN 'jewelry' THEN 3 WHEN 'accessories' THEN 4 ELSE 5 END")->with('children')->get())
@php($wwCategories = \App\Models\ProductCategory::whereNull('parent_id')->whereHas('products', fn($q)=>$q->where('status','active'))->orderByRaw("CASE slug WHEN 'new-releases' THEN 0 WHEN 'mens' THEN 1 WHEN 'womens' THEN 2 WHEN 'jewelry' THEN 3 WHEN 'accessories' THEN 4 ELSE 5 END")->with('children')->get())
@include('storefront.header')
<main id="main-content">@if(session('success'))<div class="ww-alert" role="status">{{ session('success') }}</div>@endif @if($errors->any())<div class="ww-alert" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif @yield('content')</main>
@include('storefront.footer')
<dialog id="ww-quickview" class="ww-dialog"><button class="ww-close" data-close-dialog aria-label="Close quick view">×</button><div id="ww-quickview-content"></div></dialog>
<div id="ww-toast" role="status" aria-live="polite"></div>
<script src="{{ asset('storefront/storefront.js') }}" defer></script>
@stack('page-scripts')
</body></html>
