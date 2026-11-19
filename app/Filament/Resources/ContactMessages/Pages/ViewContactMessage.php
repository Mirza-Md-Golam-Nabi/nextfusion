<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

/**
 * @property ContactMessage $record
 */
class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->markAsRead();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Reply by email')
                ->icon(Heroicon::OutlinedEnvelope)
                ->url(fn (): string => 'mailto:'.$this->record->email.'?subject='.rawurlencode('Re: '.($this->record->subject ?? 'Your message to NextFusion'))),
            DeleteAction::make(),
        ];
    }
}
