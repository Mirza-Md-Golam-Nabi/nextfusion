<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Filament\Support\LocalizedFields;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(2)
                    ->schema([
                        LocalizedFields::tabs(fn (string $locale, bool $isRequired): array => [
                            TextInput::make("client_name.{$locale}")
                                ->label('Client name')
                                ->required($isRequired)
                                ->maxLength(255),
                            TextInput::make("client_designation.{$locale}")
                                ->label('Designation')
                                ->maxLength(255),
                            TextInput::make("organization.{$locale}")
                                ->label('Organization')
                                ->maxLength(255),
                            Textarea::make("content.{$locale}")
                                ->label('Review')
                                ->required($isRequired)
                                ->rows(4),
                        ]),
                    ]),
                Section::make()
                    ->schema([
                        FileUpload::make('photo')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('testimonials')
                            ->visibility('public'),
                        Select::make('product_id')
                            ->label('Software used')
                            ->options(fn (): array => Product::query()->ordered()->get()
                                ->mapWithKeys(fn (Product $product): array => [$product->id => $product->translate('name', 'en')])
                                ->all()),
                        Select::make('rating')
                            ->options([5 => '★★★★★', 4 => '★★★★', 3 => '★★★', 2 => '★★', 1 => '★'])
                            ->default(5)
                            ->required(),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Show on website')
                            ->default(true),
                    ]),
            ]);
    }
}
