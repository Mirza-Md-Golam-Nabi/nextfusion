<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch the website language and go back to the previous page.
     */
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        abort_unless(array_key_exists($locale, config('app.supported_locales')), 404);

        $request->session()->put(SetLocale::SESSION_KEY, $locale);

        return redirect()->back(fallback: route('home'));
    }
}
