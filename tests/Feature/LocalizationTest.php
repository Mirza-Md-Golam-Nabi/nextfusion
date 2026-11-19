<?php

use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;

it('switches the website language and remembers it in the session', function () {
    $this->from(route('team'))
        ->get(route('locale.switch', 'bn'))
        ->assertRedirect(route('team'))
        ->assertSessionHas('locale', 'bn');
});

it('shows the website in Bangla by default', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale', 'bn')
            ->where('locales.0.code', 'bn')
            ->where('locales.1.code', 'en'));
});

it('shows the website in English when the visitor chose English', function () {
    $this->withSession(['locale' => 'en'])
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where('locale', 'en'));
});

it('rejects unsupported languages', function () {
    $this->get(route('locale.switch', 'fr'))->assertNotFound();
});

it('serves translated content and UI strings in Bangla', function () {
    Product::factory()->create(['name' => ['en' => 'Question Bank', 'bn' => 'প্রশ্ন ব্যাংক']]);

    $this->withSession(['locale' => 'bn'])
        ->get(route('products.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale', 'bn')
            ->where('products.0.name', 'প্রশ্ন ব্যাংক')
            ->where('translations.Home', 'হোম'));
});

it('falls back to English when a Bangla translation is missing', function () {
    $product = Product::factory()->create(['name' => ['en' => 'Prize Bond', 'bn' => '']]);

    expect($product->translate('name', 'bn'))->toBe('Prize Bond');
});
