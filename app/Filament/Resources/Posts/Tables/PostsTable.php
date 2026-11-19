<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\PostCategory;
use App\Filament\Support\LocalizedFields;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('product'))
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public'),
                LocalizedFields::column('title', 'Title')
                    ->wrap(),
                TextColumn::make('category')
                    ->badge(),
                TextColumn::make('product.slug')
                    ->label('Software')
                    ->state(fn (Post $record): ?string => $record->product?->translate('name', 'en'))
                    ->placeholder('—'),
                TextColumn::make('published_at')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->state(fn (Post $record): string => match (true) {
                        $record->published_at === null => 'Draft',
                        $record->published_at->isFuture() => 'Scheduled '.$record->published_at->format('d M Y'),
                        default => $record->published_at->format('d M Y'),
                    })
                    ->color(fn (Post $record): string => match (true) {
                        $record->published_at === null => 'gray',
                        $record->published_at->isFuture() => 'warning',
                        default => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(PostCategory::class),
                Filter::make('drafts')
                    ->label('Drafts only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('published_at')),
            ])
            ->recordActions([
                Action::make('view')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->visible(fn (Post $record): bool => $record->isPublished())
                    ->url(fn (Post $record): string => route('posts.show', $record))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
