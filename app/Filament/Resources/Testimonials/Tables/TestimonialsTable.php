<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Filament\Support\LocalizedFields;
use App\Models\Testimonial;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('product'))
            ->columns([
                ImageColumn::make('photo')
                    ->disk('public')
                    ->circular(),
                LocalizedFields::column('client_name', 'Client'),
                TextColumn::make('product_id')
                    ->label('Software')
                    ->state(fn (Testimonial $record): ?string => $record->product?->translate('name', 'en'))
                    ->placeholder('—'),
                TextColumn::make('rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state))
                    ->color('warning'),
                ToggleColumn::make('is_active')
                    ->label('Visible'),
            ])
            ->recordActions([
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
