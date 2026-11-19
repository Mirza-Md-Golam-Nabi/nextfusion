<?php

namespace App\Services\School;

use App\Models\School;
use Illuminate\Encryption\Encrypter;

/**
 * Seals a secret (e.g. an SMS provider's API key) so that only one school can
 * open it. The key is derived from that school's own secret — the school
 * opens it with its CentralSecretBox — so a captured request, a proxy log or
 * another school can't read it, on top of HTTPS and the body signature.
 */
class SchoolSecretBox
{
    public static function seal(School $school, string $value): string
    {
        $key = hash_hmac('sha256', 'sms-vendor-api-key', (string) $school->secret, true);

        return (new Encrypter($key, 'aes-256-gcm'))->encryptString($value);
    }
}
