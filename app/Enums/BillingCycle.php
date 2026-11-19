<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BillingCycle: string implements HasLabel
{
    case Monthly = 'monthly';
    case Yearly = 'yearly';
    case OneTime = 'one_time';

    public function getLabel(): string
    {
        return match ($this) {
            self::Monthly => 'Monthly',
            self::Yearly => 'Yearly',
            self::OneTime => 'One time',
        };
    }
}
