<?php

namespace App\Filament\Resources\Schools\Actions;

use App\Models\School;
use App\Services\School\PushSchoolSmsRateService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Throwable;

class PushSmsRateAction
{
    /**
     * Sends a school its per-SMS charge again — the rate is pushed on every
     * save that changes it, this is for retrying when the school was down.
     */
    public static function make(): Action
    {
        return Action::make('pushSmsRate')
            ->label('Send SMS Rate')
            ->icon(Heroicon::OutlinedChatBubbleLeftEllipsis)
            ->color('gray')
            ->visible(fn (School $record): bool => filled($record->base_url) && $record->sms_rate_per_sms !== null)
            ->action(fn (School $record) => self::push($record));
    }

    /**
     * Pushes the rate and tells the admin how it went.
     */
    public static function push(School $school): void
    {
        try {
            $appliedRate = app(PushSchoolSmsRateService::class)->handle($school);
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title("{$school->name}-এ SMS চার্জ পাঠানো যায়নি")
                ->body(PushSchoolSmsRateService::describeFailure($exception).' পরে "Send SMS Rate" দিয়ে আবার পাঠান।')
                ->danger()
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->title("{$school->name}: প্রতি SMS ৳{$appliedRate} সেট হয়েছে")
            ->success()
            ->send();
    }
}
