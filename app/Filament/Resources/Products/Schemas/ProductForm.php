<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\BillingCycle;
use App\Enums\ProductStatus;
use App\Filament\Support\LocalizedFields;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        self::generalTab(),
                        self::mediaTab(),
                        self::featuresTab(),
                        self::pricingTab(),
                        self::faqTab(),
                        self::demoTab(),
                    ]),
            ]);
    }

    private static function generalTab(): Tab
    {
        return Tab::make('General')
            ->icon(Heroicon::OutlinedInformationCircle)
            ->schema([
                LocalizedFields::tabs(fn (string $locale, bool $isRequired): array => [
                    TextInput::make("name.{$locale}")
                        ->label('Name')
                        ->required($isRequired)
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state, string $operation) use ($isRequired): void {
                            if ($isRequired && $operation === 'create') {
                                $set('slug', Str::slug($state ?? ''));
                            }
                        }),
                    TextInput::make("tagline.{$locale}")
                        ->label('Tagline')
                        ->maxLength(255),
                    Textarea::make("short_description.{$locale}")
                        ->label('Short description')
                        ->helperText('Shown on product cards and used as the SEO description.')
                        ->rows(3),
                    RichEditor::make("description.{$locale}")
                        ->label('Full description')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('products/attachments')
                        ->fileAttachmentsVisibility('public'),
                ]),
                Grid::make(3)
                    ->schema([
                        TextInput::make('slug')
                            ->required()
                            ->alphaDash()
                            ->maxLength(255)
                            ->unique(Product::class, ignoreRecord: true)
                            ->helperText('Used in the URL: /services/{slug}'),
                        Select::make('status')
                            ->options(ProductStatus::class)
                            ->default(ProductStatus::Live)
                            ->required(),
                        TextInput::make('icon')
                            ->helperText('An emoji shown when no logo is uploaded, e.g. 🏫')
                            ->maxLength(16),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_featured')
                            ->label('Show on home page')
                            ->inline(false),
                        Toggle::make('is_active')
                            ->label('Published')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }

    private static function mediaTab(): Tab
    {
        return Tab::make('Media')
            ->icon(Heroicon::OutlinedPhoto)
            ->schema([
                Grid::make(2)
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('products/logos')
                            ->visibility('public')
                            ->imageEditor(),
                        FileUpload::make('cover_image')
                            ->image()
                            ->disk('public')
                            ->directory('products/covers')
                            ->visibility('public')
                            ->imageEditor(),
                    ]),
                Repeater::make('screenshots')
                    ->relationship()
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->collapsible()
                    ->grid(2)
                    ->defaultItems(0)
                    ->addActionLabel('Add screenshot')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('products/screenshots')
                            ->visibility('public'),
                        TextInput::make('caption.en')->label('Caption (English)'),
                        TextInput::make('caption.bn')->label('Caption (বাংলা)'),
                    ]),
            ]);
    }

    private static function featuresTab(): Tab
    {
        return Tab::make('Features')
            ->icon(Heroicon::OutlinedSparkles)
            ->schema([
                Repeater::make('features')
                    ->hiddenLabel()
                    ->relationship()
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->collapsible()
                    ->defaultItems(0)
                    ->addActionLabel('Add feature')
                    ->itemLabel(fn (array $state): ?string => trim(($state['icon'] ?? '').' '.($state['title']['en'] ?? '')) ?: null)
                    ->schema([
                        TextInput::make('icon')
                            ->helperText('Emoji, e.g. 📊')
                            ->maxLength(16),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title.en')->label('Title (English)')->required(),
                                TextInput::make('title.bn')->label('Title (বাংলা)'),
                                Textarea::make('description.en')->label('Description (English)')->rows(2),
                                Textarea::make('description.bn')->label('Description (বাংলা)')->rows(2),
                            ]),
                    ]),
            ]);
    }

    private static function pricingTab(): Tab
    {
        return Tab::make('Pricing')
            ->icon(Heroicon::OutlinedCurrencyBangladeshi)
            ->schema([
                Repeater::make('pricingPlans')
                    ->hiddenLabel()
                    ->relationship()
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->collapsible()
                    ->defaultItems(0)
                    ->addActionLabel('Add pricing plan')
                    ->itemLabel(fn (array $state): ?string => $state['name']['en'] ?? null)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name.en')->label('Plan name (English)')->required(),
                                TextInput::make('name.bn')->label('Plan name (বাংলা)'),
                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('৳')
                                    ->helperText('Leave empty to show "Contact us".'),
                                Select::make('billing_cycle')
                                    ->options(BillingCycle::class)
                                    ->default(BillingCycle::Monthly)
                                    ->required(),
                                TagsInput::make('features.en')
                                    ->label('Features (English)')
                                    ->placeholder('Type and press Enter'),
                                TagsInput::make('features.bn')
                                    ->label('Features (বাংলা)')
                                    ->placeholder('লিখে Enter চাপুন'),
                                Toggle::make('is_popular')->label('Highlight as most popular'),
                            ]),
                    ]),
            ]);
    }

    private static function faqTab(): Tab
    {
        return Tab::make('FAQ')
            ->icon(Heroicon::OutlinedQuestionMarkCircle)
            ->schema([
                Repeater::make('faqs')
                    ->hiddenLabel()
                    ->relationship()
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->collapsible()
                    ->defaultItems(0)
                    ->addActionLabel('Add question')
                    ->itemLabel(fn (array $state): ?string => $state['question']['en'] ?? null)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('question.en')->label('Question (English)')->required(),
                                TextInput::make('question.bn')->label('Question (বাংলা)'),
                                Textarea::make('answer.en')->label('Answer (English)')->required()->rows(3),
                                Textarea::make('answer.bn')->label('Answer (বাংলা)')->rows(3),
                            ]),
                    ]),
            ]);
    }

    private static function demoTab(): Tab
    {
        return Tab::make('Live demo')
            ->icon(Heroicon::OutlinedPlayCircle)
            ->schema([
                Section::make()
                    ->description('Visitors can log in to the demo directly with these credentials. The demo database resets on a fixed schedule, so a countdown is shown on the website.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('demo_url')
                                    ->label('Demo URL')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('demo_reset_minutes')
                                    ->label('Demo resets every')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('minutes')
                                    ->default(30)
                                    ->helperText('Must match the reset schedule on the demo server. Leave empty to hide the countdown.'),
                            ]),
                        Repeater::make('demo_credentials')
                            ->label('Demo logins')
                            ->defaultItems(0)
                            ->addActionLabel('Add login')
                            ->columns(3)
                            ->schema([
                                TextInput::make('role')->required()->placeholder('Admin'),
                                TextInput::make('username')->required()->placeholder('admin@demo.com'),
                                TextInput::make('password')->required()->placeholder('password'),
                            ]),
                    ]),
            ]);
    }
}
