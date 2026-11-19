<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    public const CACHE_KEY = 'site-settings';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'json',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Get every setting as a key => value map (cached).
     *
     * @return array<string, mixed>
     */
    public static function allAsArray(): array
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn (): array => static::query()->pluck('value', 'key')->all(),
        );
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::allAsArray()[$key] ?? $default;
    }

    /**
     * Get a setting stored per locale (e.g. {"en": "...", "bn": "..."}) in the current locale.
     */
    public static function translated(string $key, ?string $locale = null): ?string
    {
        $value = static::get($key);

        if (! is_array($value)) {
            return $value;
        }

        $locale ??= app()->getLocale();

        return filled($value[$locale] ?? null) ? $value[$locale] : ($value[config('app.fallback_locale')] ?? null);
    }

    /**
     * Persist many settings at once.
     *
     * @param  array<string, mixed>  $values
     */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
    }
}
