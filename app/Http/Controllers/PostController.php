<?php

namespace App\Http\Controllers;

use App\Enums\PostCategory;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * List published blog posts and updates, optionally filtered by category.
     */
    public function index(Request $request): Response
    {
        $category = PostCategory::tryFrom((string) $request->query('category'));

        $posts = Post::query()
            ->published()
            ->with('product')
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString()
            ->through(fn (Post $post): array => (new PostResource($post))->resolve());

        return Inertia::render('posts/index', [
            'posts' => $posts,
            'categories' => array_map(fn (PostCategory $category): string => $category->value, PostCategory::cases()),
            'activeCategory' => $category?->value,
        ]);
    }

    /**
     * Show a single published post.
     */
    public function show(Post $post): Response
    {
        abort_unless($post->isPublished(), 404);

        $post->load('product');

        return Inertia::render('posts/show', [
            'post' => (new PostResource($post))->withContent()->resolve(),
            'relatedPosts' => PostResource::collection(
                Post::query()
                    ->published()
                    ->whereKeyNot($post->id)
                    ->with('product')
                    ->latest('published_at')
                    ->limit(3)
                    ->get(),
            )->resolve(),
        ]);
    }
}
