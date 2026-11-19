<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ProductStatus;
use App\Filament\Support\LocalizedFields;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('logo')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(fn (Product $record): string => 'data:image/svg+xml;utf8,'.rawurlencode(
                        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><text x="50%" y="55%" font-size="24" text-anchor="middle" dominant-baseline="middle">'.e($record->icon ?? '📦').'</text></svg>'
                    )),
                LocalizedFields::column('name', 'Name'),
                TextColumn::make('slug')
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                ToggleColumn::make('is_active')
                    ->label('Published'),
                TextColumn::make('features_count')
                    ->label('Features')
                    ->counts('features')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProductStatus::class),
                TernaryFilter::make('is_active')
                    ->label('Published'),
            ])
            ->recordActions([
                Action::make('view')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (Product $record): string => route('products.show', $record))
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
