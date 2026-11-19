<?php

namespace App\Models;

use App\Enums\SmsVendorDriver;
use Database\Factories\SmsResponseCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * What an SMS provider's API response code means. Written only here and
 * copied to every school, where it decides whether an SMS counts as sent.
 */
class SmsResponseCode extends Model
{
    /** @use HasFactory<SmsResponseCodeFactory> */
    use HasFactory;

    protected $fillable = [
        'driver',
        'code',
        'message',
        'is_success',
    ];

    protected function casts(): array
    {
        return [
            'driver' => SmsVendorDriver::class,
            'is_success' => 'boolean',
        ];
    }

    /**
     * The whole list in the shape a school's /api/central/sms-response-codes expects.
     *
     * @return list<array{driver: string, code: string, message: string, is_success: bool}>
     */
    public static function payload(): array
    {
        return self::query()
            ->orderBy('driver')
            ->orderBy('code')
            ->get()
            ->map(fn (self $code): array => [
                'driver' => $code->driver->value,
                'code' => (string) $code->code,
                'message' => $code->message,
                'is_success' => $code->is_success,
            ])
            ->all();
    }
}
