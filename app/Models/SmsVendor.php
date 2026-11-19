<?php

namespace App\Models;

use App\Enums\SmsVendorDriver;
use App\Services\School\SchoolSecretBox;
use Database\Factories\SmsVendorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * An SMS provider account every school sends through. Set only here and
 * pushed to the schools; the API key never leaves unencrypted.
 */
class SmsVendor extends Model
{
    /** @use HasFactory<SmsVendorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'driver',
        'api_key',
        'sender_id',
        'priority',
        'is_active',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected function casts(): array
    {
        return [
            'driver' => SmsVendorDriver::class,
            'api_key' => 'encrypted',
            'priority' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The whole list in the shape a school's /api/central/sms-vendors
     * expects, each API key sealed so only that school can open it.
     *
     * @return list<array{id: int, name: string, driver: string, api_key: string, sender_id: string, priority: int, is_active: bool}>
     */
    public static function payloadFor(School $school): array
    {
        return self::query()
            ->orderBy('priority')
            ->orderBy('id')
            ->get()
            ->map(fn (self $vendor): array => [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'driver' => $vendor->driver->value,
                'api_key' => SchoolSecretBox::seal($school, $vendor->api_key),
                'sender_id' => $vendor->sender_id,
                'priority' => $vendor->priority,
                'is_active' => $vendor->is_active,
            ])
            ->all();
    }
}
