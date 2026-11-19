<?php

use App\Filament\Pages\Settings;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\ProductFaq;
use App\Models\ProductFeature;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('redirects guests to the admin login', function () {
    auth()->logout();

    $this->get('/admin')->assertRedirect('/admin/login');
});

it('renders the dashboard and settings page', function () {
    $this->get('/admin')->assertOk();
    $this->get(Settings::getUrl())->assertOk()->assertSee('Contact information');
});

it('renders the product pages', function () {
    $product = Product::factory()->create(['name' => ['en' => 'School Management', 'bn' => 'স্কুল ম্যানেজমেন্ট']]);
    ProductFeature::factory()->for($product)->create();
    PricingPlan::factory()->for($product)->create();
    ProductFaq::factory()->for($product)->create();

    $this->get(ProductResource::getUrl('index'))->assertOk()->assertSee('School Management');
    $this->get(ProductResource::getUrl('create'))->assertOk();
    $this->get(ProductResource::getUrl('edit', ['record' => $product]))->assertOk();
});

it('renders the content pages', function () {
    $post = Post::factory()->create();
    $member = TeamMember::factory()->create();
    $testimonial = Testimonial::factory()->create();

    $this->get(PostResource::getUrl('index'))->assertOk();
    $this->get(PostResource::getUrl('create'))->assertOk();
    $this->get(PostResource::getUrl('edit', ['record' => $post]))->assertOk();
    $this->get(TeamMemberResource::getUrl('index'))->assertOk();
    $this->get(TeamMemberResource::getUrl('edit', ['record' => $member]))->assertOk();
    $this->get(TestimonialResource::getUrl('index'))->assertOk();
    $this->get(TestimonialResource::getUrl('edit', ['record' => $testimonial]))->assertOk();
});

it('marks a contact message as read when it is opened', function () {
    $message = ContactMessage::factory()->create();

    $this->get(ContactMessageResource::getUrl('index'))->assertOk()->assertSee($message->name);
    $this->get(ContactMessageResource::getUrl('view', ['record' => $message]))->assertOk()->assertSee($message->email);

    expect($message->fresh()->read_at)->not->toBeNull();
});
