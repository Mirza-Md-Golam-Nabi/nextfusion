<?php

namespace App\Services\School;

use App\Models\School;
use App\Models\SmsVendor;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class PushSchoolSmsVendorsService
{
    public function __construct(private SchoolRequestSignature $signature) {}

    /**
     * এখানকার পুরো SMS vendor তালিকা স্কুলের সার্ভারে পাঠায় — স্কুল তার vendor
     * হুবহু এই তালিকার মতো করে নেয়। প্রতিটা API key শুধু ওই স্কুলের secret
     * দিয়ে খোলা যায় এমনভাবে encrypt করা, আর পুরো body সই করা থাকে।
     *
     * @return int how many vendors the school now has, as it reported back
     *
     * @throws RuntimeException when the school has no base URL, or answers with something unusable
     * @throws ConnectionException|RequestException when the school cannot be reached or refuses the request
     */
    public function handle(School $school): int
    {
        if (blank($school->base_url)) {
            throw new RuntimeException('এই স্কুলের Base URL দেওয়া নেই।');
        }

        $body = json_encode(['vendors' => SmsVendor::payloadFor($school)]);
        $timestamp = (string) now()->timestamp;

        $response = Http::withHeaders([
            'X-Timestamp' => $timestamp,
            'X-Signature' => $this->signature->sign($school->secret, $timestamp, $body),
        ])
            ->acceptJson()
            ->withBody($body, 'application/json')
            ->connectTimeout(config('schools.pull_connect_timeout_seconds'))
            ->timeout(config('schools.pull_timeout_seconds'))
            ->post(rtrim($school->base_url, '/').config('schools.sms_vendors_path'))
            ->throw();

        $synced = $response->json('synced');

        if (! is_int($synced)) {
            throw new RuntimeException('স্কুলের উত্তরটা প্রত্যাশিত মতো নয়।');
        }

        return $synced;
    }

    /**
     * A message fit to show an admin for whatever went wrong while pushing.
     */
    public static function describeFailure(Throwable $exception): string
    {
        return match (true) {
            $exception instanceof RequestException && $exception->response->status() === 422 => 'স্কুল তালিকাটা গ্রহণ করেনি (422) — স্কুলের সফটওয়্যার পুরনো (প্রোভাইডার চেনে না), অথবা CENTRAL_SECRET মেলেনি বলে API key খোলা যায়নি।',
            default => PushSchoolSmsRateService::describeFailure($exception),
        };
    }
}
