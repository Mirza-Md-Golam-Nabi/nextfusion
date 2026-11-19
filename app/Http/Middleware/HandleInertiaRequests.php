<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            ...parent::share($request),
            'locale' => $locale,
            'locales' => collect(config('app.supported_locales'))
                ->map(fn (string $label, string $code): array => ['code' => $code, 'label' => $label])
                ->sortBy(fn (array $option): int => $option['code'] === config('app.website_locale') ? 0 : 1)
                ->values()
                ->all(),
            'translations' => fn (): array => $this->translations($locale),
            'site' => fn (): array => [
                'name' => Setting::get('site_name', config('app.name')),
                'tagline' => Setting::translated('site_tagline'),
                'email' => Setting::get('contact_email'),
                'phone' => Setting::get('contact_phone'),
                'whatsapp' => Setting::get('whatsapp_number'),
                'address' => Setting::translated('address'),
                'office_hours' => Setting::translated('office_hours'),
                'map_embed_url' => Setting::get('map_embed_url'),
                'social' => array_filter([
                    'facebook' => Setting::get('facebook_url'),
                    'youtube' => Setting::get('youtube_url'),
                    'linkedin' => Setting::get('linkedin_url'),
                ]),
            ],
            'navProducts' => fn (): array => Product::query()
                ->active()
                ->ordered()
                ->get(['id', 'slug', 'name', 'icon', 'tagline'])
                ->map(fn (Product $product): array => [
                    'slug' => $product->slug,
                    'name' => $product->translate('name'),
                    'icon' => $product->icon,
                    'tagline' => $product->translate('tagline'),
                ])
                ->all(),
        ];
    }

    /**
     * UI strings for the current locale from lang/{locale}.json.
     *
     * @return array<string, string>
     */
    private function translations(string $locale): array
    {
        $path = lang_path("{$locale}.json");

        return File::exists($path) ? (array) json_decode(File::get($path), true) : [];
    }
}
