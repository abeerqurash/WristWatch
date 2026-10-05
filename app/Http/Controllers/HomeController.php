<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Latest Blog Posts
        |--------------------------------------------------------------------------
        */

        $posts = Post::query()
            ->with(['categories', 'primaryCategory', 'author'])
            ->published()
            ->latestPosts()
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Product Card Relationships
        |--------------------------------------------------------------------------
        |
        | These are required by:
        | resources/views/partials/product-card.blade.php
        |
        */

        $productRelations = [
            'images',
            'variants',
            'options',
            'optionValues',
        ];


        /*
        |--------------------------------------------------------------------------
        | Latest Products
        |--------------------------------------------------------------------------
        |
        | Show newest ACTIVE products first.
        |
        */

        $catalogOrder = app(\App\Services\CatalogOrderService::class);
        $query = Product::query()->with($productRelations)
            ->withAvg('approvedReviews as approved_reviews_avg_rating', 'rating')
            ->withCount('approvedReviews as approved_reviews_count')
            ->where('status', 'active');
        $latestProducts = (clone $query)->orderByDesc('purchase_count')->orderBy('id')->take(6)->get();
        $popularProducts = (clone $query)->where('is_featured', true)->orderBy('id')->take(6)->get();

        /*
        |--------------------------------------------------------------------------
        | Homepage
        |--------------------------------------------------------------------------
        */

        return view('home', [
            'title' => 'WristWatch',

            'meta_description' =>
                'Shop the latest watch collections and discover timeless design at WristWatch.',

            'robots' =>
                'index, follow',

            'posts' =>
                $posts,

            'latestProducts' =>
                $latestProducts,

            'popularProducts' =>
                $popularProducts,
        ]);
    }
}
