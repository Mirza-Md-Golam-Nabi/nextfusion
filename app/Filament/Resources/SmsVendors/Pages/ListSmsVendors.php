<?php

namespace App\Filament\Resources\SmsVendors\Pages;

use App\Filament\Resources\Schools\Actions\PushToAllSchoolsAction;
use App\Filament\Resources\SmsVendors\SmsVendorResource;
use App\Models\School;
use App\Services\School\PushSchoolSmsVendorsService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListSmsVendors extends ListRecords
{
    protected static string $resource = SmsVendorResource::class;

    public function getSubheading(): ?string
    {
        return 'এখানকার vendor-ই প্রতিটা স্কুলে যায় — যোগ, বদল বা বন্ধ করার পর "সব স্কুলে পাঠান" চাপুন।';
    }

    protected function getHeaderActions(): array
    {
        return [
            PushToAllSchoolsAction::make(
                what: 'SMS Vendor',
                warning: 'প্রতিটা active স্কুলের SMS Vendor হুবহু এই তালিকার মতো হয়ে যাবে — এখানে নেই এমন vendor স্কুল থেকেও মুছে যাবে। API key প্রতিটা স্কুলের জন্য আলাদা করে encrypt হয়ে যায়।',
                push: fn (School $school): int => app(PushSchoolSmsVendorsService::class)->handle($school),
                describeFailure: fn (Throwable $exception): string => PushSchoolSmsVendorsService::describeFailure($exception),
                retryLabel: 'Send SMS Vendors',
            ),
            CreateAction::make(),
        ];
    }
}
