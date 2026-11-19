<?php

namespace App\Filament\Resources\SmsVendors;

use App\Filament\Resources\SmsVendors\Pages\CreateSmsVendor;
use App\Filament\Resources\SmsVendors\Pages\EditSmsVendor;
use App\Filament\Resources\SmsVendors\Pages\ListSmsVendors;
use App\Filament\Resources\SmsVendors\Schemas\SmsVendorForm;
use App\Filament\Resources\SmsVendors\Tables\SmsVendorsTable;
use App\Models\SmsVendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * The SMS provider accounts every school sends through. Written only here
 * and pushed to the schools — the schools can't add or edit vendors.
 */
class SmsVendorResource extends Resource
{
    protected static ?string $model = SmsVendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedServerStack;

    protected static string|UnitEnum|null $navigationGroup = 'Software';

    protected static ?string $navigationLabel = 'SMS Vendors';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SmsVendorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SmsVendorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSmsVendors::route('/'),
            'create' => CreateSmsVendor::route('/create'),
            'edit' => EditSmsVendor::route('/{record}/edit'),
        ];
    }
}
