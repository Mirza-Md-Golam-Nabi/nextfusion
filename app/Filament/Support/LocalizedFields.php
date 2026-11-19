<?php

namespace App\Filament\Support;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Helpers for editing and listing attributes stored per locale ({"en": "...", "bn": "..."}).
 */
class LocalizedFields
{
    /**
     * Render one tab per supported locale. The closure receives the locale code and whether it is the fallback (required) locale.
     *
     * @param  Closure(string $locale, bool $isFallbackLocale): array<Component|Field>  $fields
     */
    public static function tabs(Closure $fields, string $label = 'Translations'): Tabs
    {
        $fallbackLocale = config('app.fallback_locale');

        return Tabs::make($label)
            ->tabs(
                collect(config('app.supported_locales'))
                    ->map(fn (string $localeLabel, string $locale): Tab => Tab::make($localeLabel)
                        ->schema($fields($locale, $locale === $fallbackLocale)))
                    ->values()
                    ->all(),
            )
            ->columnSpanFull();
    }

    /**
     * A table column that shows a translatable attribute and searches across every locale.
     */
    public static function column(string $attribute, ?string $label = null): TextColumn
    {
        return TextColumn::make($attribute)
            ->label($label)
            ->state(fn (Model $record): mixed => $record->translate($attribute, config('app.fallback_locale')))
            ->description(function (Model $record) use ($attribute): ?string {
                $value = $record->getAttribute($attribute)['bn'] ?? null;

                return is_string($value) ? $value : null;
            })
            ->searchable(query: function (Builder $query, string $search) use ($attribute): Builder {
                return $query->where(function (Builder $query) use ($attribute, $search): void {
                    foreach (array_keys(config('app.supported_locales')) as $locale) {
                        $query->orWhere("{$attribute}->{$locale}", 'like', "%{$search}%");
                    }
                });
            });
    }
}
