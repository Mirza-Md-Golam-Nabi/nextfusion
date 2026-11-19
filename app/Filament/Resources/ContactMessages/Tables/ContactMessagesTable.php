<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('product'))
            ->recordClasses(fn (ContactMessage $record): ?string => $record->read_at === null ? 'font-semibold' : null)
            ->columns([
                IconColumn::make('read_at')
                    ->label('')
                    ->state(fn (ContactMessage $record): bool => $record->read_at !== null)
                    ->icon(fn (bool $state): Heroicon => $state ? Heroicon::OutlinedEnvelopeOpen : Heroicon::OutlinedEnvelope)
                    ->color(fn (bool $state): string => $state ? 'gray' : 'primary'),
                TextColumn::make('name')
                    ->searchable()
                    ->description(fn (ContactMessage $record): string => $record->email),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(50)
                    ->placeholder('—'),
                TextColumn::make('product_id')
                    ->label('Software')
                    ->state(fn (ContactMessage $record): ?string => $record->product?->translate('name', 'en'))
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Status')
                    ->nullable()
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
                SelectFilter::make('product_id')
                    ->label('Software')
                    ->relationship('product', 'slug'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('markAsUnread')
                    ->label('Mark unread')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('gray')
                    ->visible(fn (ContactMessage $record): bool => $record->read_at !== null)
                    ->action(fn (ContactMessage $record) => $record->update(['read_at' => null])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Mark as read')
                        ->icon(Heroicon::OutlinedEnvelopeOpen)
                        ->action(fn (Collection $records) => $records->each->markAsRead())
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
