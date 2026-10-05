@extends('layouts.storefront')
@section('title',($page->meta_title ?: $page->title).' | WristWatch')
@section('content')<div class="ww-container"><div class="ww-page-title"><h1>{{ $page->title }}</h1>@if($page->excerpt)<p>{{ $page->excerpt }}</p>@endif</div><article class="ww-blog-body ww-copy">{!! app(\App\Services\HtmlContentSanitizer::class)->clean(str_replace('Arizona Outfits','WristWatch',$page->content)) !!}</article></div>@endsection

