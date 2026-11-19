<?php

use App\Enums\PostCategory;
use App\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;

it('lists only published posts', function () {
    Post::factory()->count(2)->create();
    Post::factory()->draft()->create();
    Post::factory()->scheduled()->create();

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('posts/index')
            ->has('posts.data', 2)
            ->where('activeCategory', null));
});

it('filters posts by category', function () {
    Post::factory()->create(['category' => PostCategory::Release]);
    Post::factory()->create(['category' => PostCategory::News]);

    $this->get(route('posts.index', ['category' => 'release']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('posts.data', 1)
            ->where('posts.data.0.category', 'release')
            ->where('activeCategory', 'release'));
});

it('shows a published post with its content', function () {
    $post = Post::factory()->create(['content' => ['en' => '<p>Hello world</p>']]);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('posts/show')
            ->where('post.slug', $post->slug)
            ->where('post.content', '<p>Hello world</p>'));
});

it('hides draft and scheduled posts', function (string $state) {
    $post = Post::factory()->{$state}()->create();

    $this->get(route('posts.show', $post))->assertNotFound();
})->with(['draft', 'scheduled']);
