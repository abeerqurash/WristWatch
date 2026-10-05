<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorefrontController;
Route::post('/newsletter/subscribe', [StorefrontController::class, 'subscribe'])->middleware('throttle:public-forms')->name('storefront.subscribe');
Route::view('/faqs', 'storefront.faq')->name('storefront.faq');
Route::view('/shipping-and-returns', 'storefront.shipping')->name('storefront.shipping');
Route::redirect('/about-us', '/about');
Route::redirect('/contact-us', '/contact');
Route::redirect('/blog', '/blogs');
Route::view('/brands', 'storefront.gallery', ['galleryTitle'=>'Brands'])->name('storefront.brands');
Route::view('/lookbook', 'storefront.gallery', ['galleryTitle'=>'Lookbook'])->name('storefront.lookbook');
Route::view('/image-gallery', 'storefront.gallery', ['galleryTitle'=>'Image Gallery'])->name('storefront.gallery');
