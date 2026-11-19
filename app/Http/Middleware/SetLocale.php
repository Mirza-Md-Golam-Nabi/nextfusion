<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Apply the website language chosen by the visitor (stored in the session by LocaleController),
 * falling back to the default website language.
 */
class SetLocale
{
    public const SESSION_KEY = 'locale';

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get(self::SESSION_KEY, config('app.website_locale'));

        if (is_string($locale) && array_key_exists($locale, config('app.supported_locales'))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
