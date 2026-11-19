<?php

namespace App\Filament\Resources\Schools\Actions;

use App\Models\School;
use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Throwable;

class PushToAllSchoolsAction
{
    /**
     * A "সব স্কুলে পাঠান" button: runs $push for every active school that has
     * a base URL, then says which schools could not be reached and why.
     *
     * @param  string  $what  What is being sent, e.g. "Response Code" — used in the messages.
     * @param  Closure(School): mixed  $push  Sends to one school; throws on failure.
     * @param  Closure(Throwable): string  $describeFailure  Turns a failure into a message for the admin.
     * @param  string  $retryLabel  The per-school button to retry with, named in the failure message.
     */
    public static function make(string $what, string $warning, Closure $push, Closure $describeFailure, string $retryLabel): Action
    {
        return Action::make('pushToAllSchools')
            ->label('সব স্কুলে পাঠান')
            ->icon(Heroicon::OutlinedPaperAirplane)
            ->requiresConfirmation()
            ->modalHeading("সব স্কুলে {$what} পাঠাবেন?")
            ->modalDescription($warning)
            ->action(fn () => self::pushToAll($what, $push, $describeFailure, $retryLabel));
    }

    /**
     * @param  Closure(School): mixed  $push
     * @param  Closure(Throwable): string  $describeFailure
     */
    private static function pushToAll(string $what, Closure $push, Closure $describeFailure, string $retryLabel): void
    {
        $failures = [];
        $succeeded = 0;

        $schools = School::query()->active()->whereNotNull('base_url')->where('base_url', '!=', '')->orderBy('name')->get();

        foreach ($schools as $school) {
            try {
                $push($school);
                $succeeded++;
            } catch (Throwable $exception) {
                report($exception);
                $failures[] = "{$school->name}: ".$describeFailure($exception);
            }
        }

        if ($failures === []) {
            Notification::make()
                ->title("{$succeeded} টি স্কুলে {$what} পাঠানো হয়েছে")
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title("{$succeeded} টি স্কুলে গেছে, ".count($failures).' টিতে যায়নি')
            ->body(implode("\n", $failures)."\nস্কুলের পাতা থেকে \"{$retryLabel}\" দিয়ে আবার পাঠান।")
            ->danger()
            ->persistent()
            ->send();
    }
}
