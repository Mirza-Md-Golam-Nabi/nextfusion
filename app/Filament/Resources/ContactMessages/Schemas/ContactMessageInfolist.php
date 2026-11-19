<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make(fn (ContactMessage $record): string => $record->subject ?: 'Message')
                    ->columnSpan(2)
                    ->schema([
                        TextEntry::make('message')
                            ->hiddenLabel()
                            ->prose()
                            ->formatStateUsing(fn (string $state): string => nl2br(e($state)))
                            ->html(),
                    ]),
                Section::make('Sender')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->copyable()
                            ->url(fn (ContactMessage $record): string => 'mailto:'.$record->email),
                        TextEntry::make('phone')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('product_id')
                            ->label('Interested in')
                            ->state(fn (ContactMessage $record): ?string => $record->product?->translate('name', 'en'))
                            ->placeholder('—'),
                        TextEntry::make('created_at')
                            ->label('Received')
                            ->dateTime('d M Y, h:i A'),
                    ]),
            ]);
    }
}
