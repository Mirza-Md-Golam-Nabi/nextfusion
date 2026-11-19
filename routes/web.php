<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/services', [ProductController::class, 'index'])->name('products.index');
Route::get('/services/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/team', TeamController::class)->name('team');

Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/language/{locale}', LocaleController::class)->name('locale.switch');
