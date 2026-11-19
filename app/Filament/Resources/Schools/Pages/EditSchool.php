<?php

namespace App\Filament\Resources\Schools\Pages;

use App\Filament\Resources\Schools\Actions\PullStudentReportAction;
use App\Filament\Resources\Schools\Actions\PushSmsRateAction;
use App\Filament\Resources\Schools\Actions\PushSmsResponseCodesAction;
use App\Filament\Resources\Schools\Actions\PushSmsVendorsAction;
use App\Filament\Resources\Schools\Actions\RegenerateSecretAction;
use App\Filament\Resources\Schools\SchoolResource;
use App\Filament\Resources\Schools\Widgets\MonthlyStudentsChart;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchool extends EditRecord
{
    protected static string $resource = SchoolResource::class;

    /**
     * The month-by-month bar chart sits right under the Report History table,
     * so the numbers and their shape are read together.
     */
    protected function getFooterWidgets(): array
    {
        return [
            MonthlyStudentsChart::class,
        ];
    }

    /**
     * A changed SMS rate goes straight to the school, so the school always
     * charges what is set here.
     */
    protected function afterSave(): void
    {
        $school = $this->getRecord();

        if ($school->wasChanged('sms_rate_per_sms') && $school->sms_rate_per_sms !== null && filled($school->base_url)) {
            PushSmsRateAction::push($school);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            PullStudentReportAction::make(),
            PushSmsRateAction::make(),
            PushSmsVendorsAction::make(),
            PushSmsResponseCodesAction::make(),
            RegenerateSecretAction::make(),
            DeleteAction::make(),
        ];
    }
}
