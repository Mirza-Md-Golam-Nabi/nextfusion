<?php

namespace App\Services\School;

use App\Models\School;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class PushSchoolSmsRateService
{
    public function __construct(private SchoolRequestSignature $signature) {}

    /**
     * স্কুলের সার্ভারে প্রতি SMS-এর চার্জ পাঠিয়ে সেট করে দেয়। স্কুলের admin
     * panel থেকে এটা বদলানো যায় না — শুধু এখান থেকেই নিয়ন্ত্রিত। পুরো JSON
     * body স্কুলের secret দিয়ে সই করা থাকে, তাই পথে কেউ rate বদলাতে পারে না।
     *
     * @return float the rate the school now charges, as it reported back
     *
     * @throws RuntimeException when the school has no base URL or rate, or answers with something unusable
     * @throws ConnectionException|RequestException when the school cannot be reached or refuses the request
     */
    public function handle(School $school): float
    {
        if (blank($school->base_url)) {
            throw new RuntimeException('এই স্কুলের Base URL দেওয়া নেই।');
        }

        if ($school->sms_rate_per_sms === null) {
            throw new RuntimeException('এই স্কুলের Charge per SMS ঠিক করা হয়নি।');
        }

        $body = json_encode(['rate_per_sms' => (float) $school->sms_rate_per_sms]);
        $timestamp = (string) now()->timestamp;

        $response = Http::withHeaders([
            'X-Timestamp' => $timestamp,
            'X-Signature' => $this->signature->sign($school->secret, $timestamp, $body),
        ])
            ->acceptJson()
            ->withBody($body, 'application/json')
            ->connectTimeout(config('schools.pull_connect_timeout_seconds'))
            ->timeout(config('schools.pull_timeout_seconds'))
            ->post(rtrim($school->base_url, '/').config('schools.sms_rate_path'))
            ->throw();

        $appliedRate = $response->json('rate_per_sms');

        if (! is_numeric($appliedRate)) {
            throw new RuntimeException('স্কুলের উত্তরটা প্রত্যাশিত মতো নয়।');
        }

        return (float) $appliedRate;
    }

    /**
     * A message fit to show an admin for whatever went wrong while pushing.
     * The school answers 403 (not 401) when the signature does not match.
     */
    public static function describeFailure(Throwable $exception): string
    {
        return match (true) {
            $exception instanceof RequestException && $exception->response->status() === 403 => 'স্কুল request গ্রহণ করেনি (403) — স্কুলের .env-এর CENTRAL_SCHOOL_ID ও CENTRAL_SECRET মিলিয়ে দেখুন।',
            default => PullSchoolStudentReportService::describeFailure($exception),
        };
    }
}
