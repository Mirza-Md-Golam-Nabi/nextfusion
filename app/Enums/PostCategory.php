<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PostCategory: string implements HasColor, HasLabel
{
    case News = 'news';
    case Update = 'update';
    case Release = 'release';
    case Tutorial = 'tutorial';

    public function getLabel(): string
    {
        return match ($this) {
            self::News => 'News',
            self::Update => 'Update',
            self::Release => 'Release',
            self::Tutorial => 'Tutorial',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::News => 'info',
            self::Update => 'warning',
            self::Release => 'success',
            self::Tutorial => 'gray',
        };
    }
}
