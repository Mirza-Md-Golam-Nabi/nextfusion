<?php

namespace App\Filament\Resources\Schools\Actions;

use App\Models\School;
use App\Services\School\PushSchoolSmsVendorsService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Throwable;

class PushSmsVendorsAction
{
    /**
     * Sends one school the SMS vendors (API key, Sender ID) — e.g. a newly
     * added school, or one that was down when the vendors were last sent.
     */
    public static function make(): Action
    {
        return Action::make('pushSmsVendors')
            ->label('Send SMS Vendors')
            ->icon(Heroicon::OutlinedServerStack)
            ->color('gray')
            ->visible(fn (School $record): bool => filled($record->base_url))
            ->action(fn (School $record) => self::push($record));
    }

    /**
     * Pushes the vendors and tells the admin how it went.
     */
    public static function push(School $school): void
    {
        try {
            $synced = app(PushSchoolSmsVendorsService::class)->handle($school);
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title("{$school->name}-এ SMS Vendor পাঠানো যায়নি")
                ->body(PushSchoolSmsVendorsService::describeFailure($exception))
                ->danger()
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->title("{$school->name}: {$synced} টি SMS Vendor সেট হয়েছে")
            ->success()
            ->send();
    }
}
