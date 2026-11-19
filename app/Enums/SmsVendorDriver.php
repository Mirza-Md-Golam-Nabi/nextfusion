<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

/**
 * The SMS providers the School Management software can talk to. The values
 * must match the school app's own SmsVendorDriver enum — a school rejects
 * codes for a provider it does not know.
 */
enum SmsVendorDriver: string implements HasLabel
{
    case BulkSmsBd = 'bulksmsbd';

    public function getLabel(): string
    {
        return match ($this) {
            self::BulkSmsBd => 'BulkSMSBD',
        };
    }
}
