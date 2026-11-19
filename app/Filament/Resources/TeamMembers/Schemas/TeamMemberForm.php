<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Filament\Support\LocalizedFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()
                    ->columnSpan(2)
                    ->schema([
                        LocalizedFields::tabs(fn (string $locale, bool $isRequired): array => [
                            TextInput::make("name.{$locale}")
                                ->label('Name')
                                ->required($isRequired)
                                ->maxLength(255),
                            TextInput::make("designation.{$locale}")
                                ->label('Designation')
                                ->required($isRequired)
                                ->maxLength(255),
                            Textarea::make("bio.{$locale}")
                                ->label('Short bio')
                                ->rows(3),
                        ]),
                        Section::make('Contact & social links')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('email')->email(),
                                        TextInput::make('facebook_url')->label('Facebook')->url(),
                                        TextInput::make('linkedin_url')->label('LinkedIn')->url(),
                                        TextInput::make('github_url')->label('GitHub')->url(),
                                    ]),
                            ]),
                    ]),
                Section::make()
                    ->schema([
                        FileUpload::make('photo')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('team')
                            ->visibility('public')
                            ->imageEditor(),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Show on website')
                            ->default(true),
                    ]),
            ]);
    }
}
