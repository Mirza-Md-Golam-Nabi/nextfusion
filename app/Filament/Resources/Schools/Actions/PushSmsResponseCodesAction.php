<?php

namespace App\Filament\Resources\Schools\Actions;

use App\Models\School;
use App\Services\School\PushSchoolSmsResponseCodesService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Throwable;

class PushSmsResponseCodesAction
{
    /**
     * Sends one school the full SMS response code list — e.g. a newly added
     * school, or one that was down when the codes were last sent.
     */
    public static function make(): Action
    {
        return Action::make('pushSmsResponseCodes')
            ->label('Send Response Codes')
            ->icon(Heroicon::OutlinedCodeBracketSquare)
            ->color('gray')
            ->visible(fn (School $record): bool => filled($record->base_url))
            ->action(fn (School $record) => self::push($record));
    }

    /**
     * Pushes the codes and tells the admin how it went.
     */
    public static function push(School $school): void
    {
        try {
            $synced = app(PushSchoolSmsResponseCodesService::class)->handle($school);
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title("{$school->name}-এ Response Code পাঠানো যায়নি")
                ->body(PushSchoolSmsResponseCodesService::describeFailure($exception))
                ->danger()
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->title("{$school->name}: {$synced} টি Response Code সেট হয়েছে")
            ->success()
            ->send();
    }
}
