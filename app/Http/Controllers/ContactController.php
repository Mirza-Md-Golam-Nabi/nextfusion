<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Show the contact page. `?product=slug` preselects the software the visitor is asking about.
     */
    public function show(Request $request): Response
    {
        return Inertia::render('contact', [
            'selectedProduct' => $request->query('product'),
        ]);
    }

    /**
     * Store a message from the contact form.
     */
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ContactMessage::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'product_id' => filled($validated['product'] ?? null)
                ? Product::query()->where('slug', $validated['product'])->value('id')
                : null,
        ]);

        Inertia::flash('success', __('Thank you! Your message has been sent. We will contact you soon.'));

        return to_route('contact');
    }
}
