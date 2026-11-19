<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\TeamMemberResource;
use App\Http\Resources\TestimonialResource;
use App\Models\Post;
use App\Models\Product;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('home', [
            'products' => ProductCardResource::collection(
                Product::query()->active()->where('is_featured', true)->ordered()->get(),
            )->resolve(),
            'testimonials' => TestimonialResource::collection(
                Testimonial::query()->active()->ordered()->with('product')->limit(6)->get(),
            )->resolve(),
            'posts' => PostResource::collection(
                Post::query()->published()->with('product')->latest('published_at')->limit(3)->get(),
            )->resolve(),
            'team' => TeamMemberResource::collection(
                TeamMember::query()->active()->ordered()->limit(4)->get(),
            )->resolve(),
        ]);
    }
}
