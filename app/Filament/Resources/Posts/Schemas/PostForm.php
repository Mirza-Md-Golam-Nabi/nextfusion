<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostCategory;
use App\Filament\Support\LocalizedFields;
use App\Models\Post;
use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
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
                            TextInput::make("title.{$locale}")
                                ->label('Title')
                                ->required($isRequired)
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state, string $operation) use ($isRequired): void {
                                    if ($isRequired && $operation === 'create') {
                                        $set('slug', Str::slug($state ?? ''));
                                    }
                                }),
                            Textarea::make("excerpt.{$locale}")
                                ->label('Excerpt')
                                ->rows(2)
                                ->maxLength(500),
                            RichEditor::make("content.{$locale}")
                                ->label('Content')
                                ->required($isRequired)
                                ->fileAttachmentsDisk('public')
                                ->fileAttachmentsDirectory('posts/attachments')
                                ->fileAttachmentsVisibility('public'),
                        ]),
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Publishing')
                            ->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->alphaDash()
                                    ->maxLength(255)
                                    ->unique(Post::class, ignoreRecord: true),
                                Select::make('category')
                                    ->options(PostCategory::class)
                                    ->default(PostCategory::News)
                                    ->required(),
                                Select::make('product_id')
                                    ->label('Related software')
                                    ->options(fn (): array => Product::query()->ordered()->get()
                                        ->mapWithKeys(fn (Product $product): array => [$product->id => $product->translate('name', 'en')])
                                        ->all())
                                    ->searchable(),
                                DateTimePicker::make('published_at')
                                    ->label('Publish at')
                                    ->default(now())
                                    ->helperText('Leave empty to keep as draft. A future date schedules the post.'),
                            ]),
                        Section::make('Cover image')
                            ->schema([
                                FileUpload::make('cover_image')
                                    ->hiddenLabel()
                                    ->image()
                                    ->disk('public')
                                    ->directory('posts')
                                    ->visibility('public')
                                    ->imageEditor(),
                            ]),
                    ]),
            ]);
    }
}
