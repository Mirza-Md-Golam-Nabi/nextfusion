<?php

use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Inertia\Testing\AssertableInertia as Assert;

it('shows featured products, testimonials and team on the home page', function () {
    Product::factory()->featured()->create(['name' => ['en' => 'School Management']]);
    Product::factory()->create();
    Product::factory()->featured()->inactive()->create();
    Testimonial::factory()->count(2)->create();
    Testimonial::factory()->inactive()->create();
    TeamMember::factory()->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('home')
            ->has('products', 1)
            ->where('products.0.name', 'School Management')
            ->has('testimonials', 2)
            ->has('team', 1)
            ->has('navProducts', 2)
            ->where('locale', 'bn'));
});

it('lists only active products on the services page in sort order', function () {
    Product::factory()->create(['name' => ['en' => 'Second'], 'sort_order' => 2]);
    Product::factory()->create(['name' => ['en' => 'First'], 'sort_order' => 1]);
    Product::factory()->inactive()->create();

    $this->get(route('products.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('products/index')
            ->has('products', 2)
            ->where('products.0.name', 'First')
            ->where('products.1.name', 'Second'));
});

it('shows product details with features, pricing and demo credentials', function () {
    $product = Product::factory()->create([
        'demo_url' => 'https://demo.example.com',
        'demo_reset_minutes' => 30,
        'demo_credentials' => [['role' => 'Admin', 'username' => 'admin@demo.com', 'password' => 'secret']],
    ]);
    ProductFeature::factory()->count(3)->for($product)->create();
    PricingPlan::factory()->for($product)->create(['features' => ['en' => ['Unlimited students']]]);

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('products/show')
            ->where('product.slug', $product->slug)
            ->has('product.features', 3)
            ->where('product.pricing_plans.0.features', ['Unlimited students'])
            ->where('product.demo.url', 'https://demo.example.com')
            ->where('product.demo.reset_minutes', 30)
            ->where('product.demo.credentials.0.username', 'admin@demo.com'));
});

it('returns 404 for an inactive product', function () {
    $product = Product::factory()->inactive()->create();

    $this->get(route('products.show', $product))->assertNotFound();
});

it('lists active team members', function () {
    TeamMember::factory()->count(3)->create();
    TeamMember::factory()->inactive()->create();

    $this->get(route('team'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('team')->has('members', 3));
});
