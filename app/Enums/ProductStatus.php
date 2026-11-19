<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductStatus: string implements HasColor, HasLabel
{
    case Live = 'live';
    case Upcoming = 'upcoming';

    public function getLabel(): string
    {
        return match ($this) {
            self::Live => 'Live',
            self::Upcoming => 'Upcoming',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Live => 'success',
            self::Upcoming => 'warning',
        };
    }
}
