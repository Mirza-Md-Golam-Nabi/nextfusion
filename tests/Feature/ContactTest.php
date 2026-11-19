<?php

use App\Models\ContactMessage;
use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the contact page with a preselected product', function () {
    $this->get(route('contact', ['product' => 'school-management']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('contact')
            ->where('selectedProduct', 'school-management'));
});

it('stores a contact message linked to the selected product', function () {
    $product = Product::factory()->create();

    $this->post(route('contact.store'), [
        'name' => 'Rahim',
        'email' => 'rahim@example.com',
        'phone' => '01700000000',
        'product' => $product->slug,
        'subject' => 'Pricing',
        'message' => 'I would like to know the price for my school.',
    ])->assertRedirect(route('contact'));

    $message = ContactMessage::query()->sole();

    expect($message->name)->toBe('Rahim')
        ->and($message->product_id)->toBe($product->id)
        ->and($message->read_at)->toBeNull();
});

it('validates the contact form', function () {
    $this->post(route('contact.store'), [
        'name' => '',
        'email' => 'not-an-email',
        'product' => 'does-not-exist',
        'message' => 'short',
    ])->assertSessionHasErrors(['name', 'email', 'product', 'message']);

    expect(ContactMessage::query()->count())->toBe(0);
});

it('shows validation messages in Bangla when the site language is Bangla', function () {
    $this->withSession(['locale' => 'bn'])
        ->post(route('contact.store'), ['email' => 'rahim@example.com', 'message' => 'Hello there, NextFusion!'])
        ->assertSessionHasErrors(['name' => 'নাম দেওয়া আবশ্যক।']);
});
