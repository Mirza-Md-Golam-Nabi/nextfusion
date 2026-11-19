<?php

namespace App\Filament\Resources\SmsResponseCodes\Pages;

use App\Filament\Resources\Schools\Actions\PushToAllSchoolsAction;
use App\Filament\Resources\SmsResponseCodes\SmsResponseCodeResource;
use App\Models\School;
use App\Services\School\PushSchoolSmsResponseCodesService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Throwable;

class ManageSmsResponseCodes extends ManageRecords
{
    protected static string $resource = SmsResponseCodeResource::class;

    public function getSubheading(): ?string
    {
        return 'এখানে লেখা কোডই প্রতিটা স্কুলে যায় — বদলানোর পর "সব স্কুলে পাঠান" চাপুন।';
    }

    protected function getHeaderActions(): array
    {
        return [
            PushToAllSchoolsAction::make(
                what: 'Response Code',
                warning: 'প্রতিটা active স্কুলের Response Code টেবিল হুবহু এই তালিকার মতো হয়ে যাবে — এখানে নেই এমন কোড স্কুল থেকেও মুছে যাবে।',
                push: fn (School $school): int => app(PushSchoolSmsResponseCodesService::class)->handle($school),
                describeFailure: fn (Throwable $exception): string => PushSchoolSmsResponseCodesService::describeFailure($exception),
                retryLabel: 'Send Response Codes',
            ),
            CreateAction::make()
                ->modalWidth('md'),
        ];
    }
}
