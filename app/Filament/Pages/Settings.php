<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Site-wide contact information and social links shown in the website header, footer and contact page.
 *
 * @property-read Schema $form
 */
class Settings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?string $title = 'Site settings';

    /**
     * Keys managed by this page. Translatable values (tagline, address, office hours) are stored per locale.
     *
     * @var list<string>
     */
    private const KEYS = [
        'site_tagline', 'contact_email', 'contact_phone', 'whatsapp_number', 'address', 'office_hours',
        'facebook_url', 'youtube_url', 'linkedin_url', 'map_embed_url',
    ];

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(collect(self::KEYS)->mapWithKeys(fn (string $key): array => [$key => Setting::get($key)])->all());
    }

    public function form(Schema $schema): Schema
    {
        $locales = config('app.supported_locales');

        $perLocale = fn (string $key, string $label): Grid => Grid::make(count($locales))
            ->schema(collect($locales)->map(
                fn (string $localeLabel, string $locale): TextInput => TextInput::make("{$key}.{$locale}")->label("{$label} ({$localeLabel})"),
            )->values()->all());

        return $schema
            ->statePath('data')
            ->components([
                Section::make('General')
                    ->schema([
                        $perLocale('site_tagline', 'Tagline'),
                    ]),
                Section::make('Contact information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('contact_email')->label('Email')->email(),
                                TextInput::make('contact_phone')->label('Phone')->tel(),
                                TextInput::make('whatsapp_number')
                                    ->label('WhatsApp number')
                                    ->helperText('Country code without "+", e.g. 8801700000000'),
                            ]),
                        $perLocale('address', 'Address'),
                        $perLocale('office_hours', 'Office hours'),
                        TextInput::make('map_embed_url')
                            ->label('Google Maps embed URL')
                            ->url()
                            ->helperText('Google Maps → Share → Embed a map → copy only the src="…" link.'),
                    ]),
                Section::make('Social links')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('facebook_url')->label('Facebook')->url(),
                                TextInput::make('youtube_url')->label('YouTube')->url(),
                                TextInput::make('linkedin_url')->label('LinkedIn')->url(),
                            ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save settings')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        Setting::setMany($this->form->getState());

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
