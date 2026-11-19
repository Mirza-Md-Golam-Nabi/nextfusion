<?php

use App\Enums\SmsVendorDriver;
use App\Filament\Resources\Schools\Pages\EditSchool;
use App\Filament\Resources\SmsResponseCodes\Pages\ManageSmsResponseCodes;
use App\Models\School;
use App\Models\SmsResponseCode;
use App\Models\User;
use App\Services\School\PushSchoolSmsResponseCodesService;
use Database\Seeders\SmsResponseCodeSeeder;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

beforeEach(function () {
    $this->school = School::factory()->create([
        'name' => 'Al Helal Academy',
        'secret' => 'test-secret',
        'base_url' => 'https://school.test',
    ]);

    $this->actingAs(User::factory()->create());
});

it('seeds every BulkSMSBD code with only 202 as success', function () {
    $this->seed(SmsResponseCodeSeeder::class);
    $this->seed(SmsResponseCodeSeeder::class);

    expect(SmsResponseCode::count())->toBe(20)
        ->and(SmsResponseCode::where('is_success', true)->pluck('code')->all())->toBe(['202']);
});

it('adds a response code but not a duplicate one', function () {
    $this->seed(SmsResponseCodeSeeder::class);

    Livewire::test(ManageSmsResponseCodes::class)
        ->assertCanSeeTableRecords(SmsResponseCode::all())
        ->callAction(CreateAction::class, ['driver' => 'bulksmsbd', 'code' => '1040', 'message' => 'New provider error', 'is_success' => false])
        ->assertHasNoActionErrors()
        ->callAction(CreateAction::class, ['driver' => 'bulksmsbd', 'code' => '1007', 'message' => 'Duplicate', 'is_success' => false])
        ->assertHasActionErrors(['code' => 'unique']);

    expect(SmsResponseCode::where('code', '1040')->sole()->message)->toBe('New provider error')
        ->and(SmsResponseCode::where('code', '1007')->sole()->message)->toBe('Balance Insufficient');
});

it('sends the full code list to the school with the whole body signed', function () {
    SmsResponseCode::factory()->success()->create(['code' => '202', 'message' => 'Submitted']);
    SmsResponseCode::factory()->create(['code' => '1007', 'message' => 'Balance Insufficient']);
    Http::fake(['school.test/*' => Http::response(['synced' => 2])]);

    expect(app(PushSchoolSmsResponseCodesService::class)->handle($this->school))->toBe(2);

    /** @var Request $request */
    [$request] = Http::recorded()->sole();

    expect($request->url())->toBe('https://school.test/api/central/sms-response-codes')
        ->and($request->method())->toBe('POST')
        ->and($request->header('X-Signature')[0])->toBe(hash_hmac('sha256', $request->header('X-Timestamp')[0].'.'.$request->body(), 'test-secret'))
        ->and($request['codes'])->toEqualCanonicalizing([
            ['driver' => 'bulksmsbd', 'code' => '202', 'message' => 'Submitted', 'is_success' => true],
            ['driver' => 'bulksmsbd', 'code' => '1007', 'message' => 'Balance Insufficient', 'is_success' => false],
        ]);
});

it('sends the codes to every active school and reports the ones that failed', function () {
    SmsResponseCode::factory()->create(['driver' => SmsVendorDriver::BulkSmsBd]);
    School::factory()->create(['name' => 'Down School', 'base_url' => 'https://down.test']);
    School::factory()->inactive()->create(['base_url' => 'https://inactive.test']);
    School::factory()->create(['base_url' => null]);

    Http::fake([
        'school.test/*' => Http::response(['synced' => 1]),
        'down.test/*' => Http::response([], 500),
    ]);

    Livewire::test(ManageSmsResponseCodes::class)
        ->callAction('pushToAllSchools')
        ->assertNotified('1 টি স্কুলে গেছে, 1 টিতে যায়নি');

    Http::assertSentCount(2);
    Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), 'inactive.test'));
});

it('resends the codes from the school page', function () {
    Http::fake(['school.test/*' => Http::response(['synced' => 0])]);

    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->callAction(TestAction::make('pushSmsResponseCodes'))
        ->assertNotified('Al Helal Academy: 0 টি Response Code সেট হয়েছে');

    Http::assertSentCount(1);
});

it('explains why the school did not take the codes', function (Closure $fake, string $expected) {
    $fake();

    $exception = rescue(fn () => app(PushSchoolSmsResponseCodesService::class)->handle($this->school), fn (Throwable $e) => $e, report: false);

    expect(PushSchoolSmsResponseCodesService::describeFailure($exception))->toContain($expected);
})->with([
    'school refuses the signature' => [fn () => Http::fake(['school.test/*' => Http::response([], 403)]), '403'],
    'school does not know a provider' => [fn () => Http::fake(['school.test/*' => Http::response([], 422)]), '422'],
    'answer is not a count' => [fn () => Http::fake(['school.test/*' => Http::response(['hello' => 'world'])]), 'প্রত্যাশিত মতো নয়'],
]);
