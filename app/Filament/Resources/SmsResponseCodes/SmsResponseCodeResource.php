<?php

namespace App\Filament\Resources\SmsResponseCodes;

use App\Enums\SmsVendorDriver;
use App\Filament\Resources\SmsResponseCodes\Pages\ManageSmsResponseCodes;
use App\Models\SmsResponseCode;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

/**
 * What each SMS provider's API response codes mean. Written only here and
 * sent to every school — the schools themselves can't edit them.
 */
class SmsResponseCodeResource extends Resource
{
    protected static ?string $model = SmsResponseCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracketSquare;

    protected static string|UnitEnum|null $navigationGroup = 'Software';

    protected static ?string $navigationLabel = 'SMS Response Codes';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('driver')
                    ->label('Provider')
                    ->options(SmsVendorDriver::class)
                    ->default(SmsVendorDriver::BulkSmsBd->value)
                    ->required()
                    ->native(false)
                    ->live(),
                TextInput::make('code')
                    ->required()
                    ->maxLength(20)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('driver', $get('driver') instanceof SmsVendorDriver ? $get('driver')->value : $get('driver')),
                    ),
                TextInput::make('message')
                    ->label('Meaning')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_success')
                    ->label('Means the SMS was accepted')
                    ->default(false),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Numeric order (202 before 1001), which a plain string sort would break.
            ->defaultSort(fn (Builder $query): Builder => $query->orderBy('driver')->orderByRaw('LENGTH(code)')->orderBy('code'))
            ->paginated([25, 50, 'all'])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('driver')
                    ->label('Provider')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('code')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('message')
                    ->label('Meaning')
                    ->searchable()
                    ->wrap(),
                IconColumn::make('is_success')
                    ->label('Success')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('driver')
                    ->label('Provider')
                    ->options(SmsVendorDriver::class),
            ])
            ->recordActions([
                EditAction::make()->iconButton()->modalWidth('md'),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSmsResponseCodes::route('/'),
        ];
    }
}
