<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * List every published software product.
     */
    public function index(): Response
    {
        return Inertia::render('products/index', [
            'products' => ProductCardResource::collection(Product::query()->active()->ordered()->get())->resolve(),
        ]);
    }

    /**
     * Show the details of one software product.
     */
    public function show(Product $product): Response
    {
        abort_unless($product->is_active, 404);

        $product->load(['features', 'screenshots', 'pricingPlans', 'faqs']);

        return Inertia::render('products/show', [
            'product' => (new ProductResource($product))->resolve(),
            'testimonials' => TestimonialResource::collection(
                $product->testimonials()->active()->ordered()->get(),
            )->resolve(),
            'posts' => PostResource::collection(
                $product->posts()->published()->latest('published_at')->limit(3)->get(),
            )->resolve(),
            'otherProducts' => ProductCardResource::collection(
                Product::query()->active()->whereKeyNot($product->id)->ordered()->limit(3)->get(),
            )->resolve(),
        ]);
    }
}
