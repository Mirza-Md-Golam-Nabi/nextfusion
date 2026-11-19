<?php

return [

    /*
     * How old a school's signed report may be, in seconds, before it is
     * rejected — limits how long a captured request can be replayed.
     */
    'signature_tolerance_seconds' => (int) env('SCHOOL_SIGNATURE_TOLERANCE_SECONDS', 300),

    /*
     * Schools push their student count once a month. A school with no report
     * for longer than this is flagged — its server or scheduler likely needs a look.
     */
    'stale_after_days' => (int) env('SCHOOL_REPORT_STALE_AFTER_DAYS', 35),

    /*
     * The endpoint every school installation exposes for the central app to
     * pull its student count from, relative to the school's base URL.
     */
    'pull_path' => '/api/central/student-report',

    /*
     * The endpoint every school installation exposes for the central app to
     * set its per-SMS charge, relative to the school's base URL.
     */
    'sms_rate_path' => '/api/central/sms-rate',

    /*
     * The endpoint every school installation exposes for the central app to
     * replace its SMS provider response codes, relative to the school's base URL.
     */
    'sms_response_codes_path' => '/api/central/sms-response-codes',

    /*
     * The endpoint every school installation exposes for the central app to
     * replace its SMS vendors (API key, Sender ID), relative to the school's base URL.
     */
    'sms_vendors_path' => '/api/central/sms-vendors',

    'pull_connect_timeout_seconds' => 10,

    'pull_timeout_seconds' => 20,

];
