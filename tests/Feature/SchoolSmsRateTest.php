<?php

use App\Filament\Resources\Schools\Pages\EditSchool;
use App\Models\School;
use App\Models\User;
use App\Services\School\PushSchoolSmsRateService;
use Filament\Actions\Testing\TestAction;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

beforeEach(function () {
    $this->school = School::factory()->create([
        'name' => 'Al Helal Academy',
        'school_code' => 'school-42',
        'secret' => 'test-secret',
        'base_url' => 'https://school.test',
    ]);

    $this->actingAs(User::factory()->create());
});

it('sends the per-SMS charge to the school with the whole body signed', function () {
    Http::fake(['school.test/*' => Http::response(['rate_per_sms' => 0.35, 'balance' => 0, 'sms_count' => 0])]);

    $this->school->update(['sms_rate_per_sms' => 0.35]);

    expect(app(PushSchoolSmsRateService::class)->handle($this->school))->toBe(0.35);

    Http::assertSent(function (Request $request): bool {
        $expected = hash_hmac('sha256', $request->header('X-Timestamp')[0].'.'.$request->body(), 'test-secret');

        return $request->url() === 'https://school.test/api/central/sms-rate'
            && $request->method() === 'POST'
            && $request['rate_per_sms'] === 0.35
            && $request->header('X-Signature')[0] === $expected;
    });
});

it('pushes the rate to the school when it is changed on the school page', function () {
    Http::fake(['school.test/*' => Http::response(['rate_per_sms' => 0.3])]);

    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->fillForm(['sms_rate_per_sms' => 0.3])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified('Al Helal Academy: প্রতি SMS ৳0.3 সেট হয়েছে');

    expect((float) $this->school->refresh()->sms_rate_per_sms)->toBe(0.3);
    Http::assertSentCount(1);
});

it('does not contact the school when the rate is left unchanged', function () {
    Http::fake();
    $this->school->update(['sms_rate_per_sms' => 0.25]);

    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->fillForm(['name' => 'Renamed Academy'])
        ->call('save')
        ->assertHasNoFormErrors();

    Http::assertNothingSent();
});

it('keeps the saved rate and says why when the school cannot be reached', function (Closure $fake, string $expected) {
    $fake();

    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->fillForm(['sms_rate_per_sms' => 0.3])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect((float) $this->school->refresh()->sms_rate_per_sms)->toBe(0.3)
        ->and(PushSchoolSmsRateService::describeFailure(rescue(fn () => app(PushSchoolSmsRateService::class)->handle($this->school), fn (Throwable $e) => $e, report: false)))
        ->toContain($expected);
})->with([
    'school refuses the signature' => [fn () => Http::fake(['school.test/*' => Http::response(['message' => 'This action is unauthorized.'], 403)]), '403'],
    'school server is down' => [fn () => Http::fake(fn () => throw new ConnectionException('timed out')), 'সংযোগ করা যায়নি'],
    'answer is not a rate' => [fn () => Http::fake(['school.test/*' => Http::response(['hello' => 'world'])]), 'প্রত্যাশিত মতো নয়'],
]);

it('rejects an unusable rate on the school page', function (mixed $rate) {
    Http::fake();

    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->fillForm(['sms_rate_per_sms' => $rate])
        ->call('save')
        ->assertHasFormErrors(['sms_rate_per_sms']);

    Http::assertNothingSent();
})->with([-1, 101, 'abc']);

it('resends the rate from the school page', function () {
    Http::fake(['school.test/*' => Http::response(['rate_per_sms' => 0.25])]);
    $this->school->update(['sms_rate_per_sms' => 0.25]);

    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->callAction(TestAction::make('pushSmsRate'))
        ->assertNotified('Al Helal Academy: প্রতি SMS ৳0.25 সেট হয়েছে');

    Http::assertSentCount(1);
});

it('hides the resend button until a rate is set', function () {
    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->assertActionHidden(TestAction::make('pushSmsRate'));
});
