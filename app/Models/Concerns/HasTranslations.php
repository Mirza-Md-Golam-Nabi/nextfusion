<?php

namespace App\Models\Concerns;

/**
 * Stores translatable attributes as JSON objects keyed by locale (e.g. {"en": "...", "bn": "..."}).
 *
 * Models using this trait must declare a `$translatable` property listing the translatable attributes.
 */
trait HasTranslations
{
    /**
     * Get the value of a translatable attribute for the given locale, falling back to the fallback locale.
     */
    public function translate(string $attribute, ?string $locale = null): mixed
    {
        $values = $this->getAttribute($attribute);

        if (! is_array($values)) {
            return $values;
        }

        $locale ??= app()->getLocale();
        $fallbackLocale = config('app.fallback_locale');

        foreach ([$locale, $fallbackLocale] as $candidateLocale) {
            if (filled($values[$candidateLocale] ?? null)) {
                return $values[$candidateLocale];
            }
        }

        return collect($values)->first(fn (mixed $value): bool => filled($value));
    }

    /**
     * Cast every translatable attribute to an array.
     */
    public function initializeHasTranslations(): void
    {
        $this->mergeCasts(
            collect($this->translatable)->mapWithKeys(fn (string $attribute): array => [$attribute => 'array'])->all(),
        );
    }
}
