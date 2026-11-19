<?php

use App\Filament\Resources\Schools\Pages\EditSchool;
use App\Filament\Resources\SmsVendors\Pages\CreateSmsVendor;
use App\Filament\Resources\SmsVendors\Pages\EditSmsVendor;
use App\Filament\Resources\SmsVendors\Pages\ListSmsVendors;
use App\Models\School;
use App\Models\SmsVendor;
use App\Models\User;
use App\Services\School\PushSchoolSmsVendorsService;
use Filament\Actions\Testing\TestAction;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
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

/**
 * Opens a sealed API key the way the school does.
 */
function openAsSchool(string $sealed, string $secret): string
{
    return (new Encrypter(hash_hmac('sha256', 'sms-vendor-api-key', $secret, true), 'aes-256-gcm'))->decryptString($sealed);
}

it('adds a vendor with an encrypted API key that is never shown again', function () {
    Livewire::test(CreateSmsVendor::class)
        ->fillForm([
            'name' => 'BulkSMSBD',
            'driver' => 'bulksmsbd',
            'sender_id' => '8809617613451',
            'api_key' => 'plain-api-key',
            'priority' => 1,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $vendor = SmsVendor::sole();

    expect($vendor->api_key)->toBe('plain-api-key')
        ->and($vendor->getRawOriginal('api_key'))->not->toContain('plain-api-key');

    Livewire::test(EditSmsVendor::class, ['record' => $vendor->id])
        ->assertSchemaStateSet(['api_key' => null])
        ->fillForm(['sender_id' => '8809600000000'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($vendor->refresh())->api_key->toBe('plain-api-key')->sender_id->toBe('8809600000000');
});

it('sends the vendors with each API key sealed for that school only', function () {
    $vendor = SmsVendor::factory()->create(['api_key' => 'plain-api-key', 'sender_id' => '8809617613451']);
    Http::fake(['school.test/*' => Http::response(['synced' => 1])]);

    expect(app(PushSchoolSmsVendorsService::class)->handle($this->school))->toBe(1);

    /** @var Request $request */
    [$request] = Http::recorded()->sole();
    $sent = $request['vendors'][0];

    expect($request->url())->toBe('https://school.test/api/central/sms-vendors')
        ->and($request->header('X-Signature')[0])->toBe(hash_hmac('sha256', $request->header('X-Timestamp')[0].'.'.$request->body(), 'test-secret'))
        ->and($request->body())->not->toContain('plain-api-key')
        ->and($sent)->toMatchArray(['id' => $vendor->id, 'driver' => 'bulksmsbd', 'sender_id' => '8809617613451', 'priority' => 1, 'is_active' => true])
        ->and(openAsSchool($sent['api_key'], 'test-secret'))->toBe('plain-api-key')
        ->and(fn () => openAsSchool($sent['api_key'], 'other-secret'))->toThrow(DecryptException::class);
});

it('sends the vendors to every active school and reports the ones that failed', function () {
    SmsVendor::factory()->create();
    School::factory()->create(['name' => 'Down School', 'base_url' => 'https://down.test']);
    School::factory()->inactive()->create(['base_url' => 'https://inactive.test']);

    Http::fake([
        'school.test/*' => Http::response(['synced' => 1]),
        'down.test/*' => Http::response([], 422),
    ]);

    Livewire::test(ListSmsVendors::class)
        ->callAction('pushToAllSchools')
        ->assertNotified('1 টি স্কুলে গেছে, 1 টিতে যায়নি');

    Http::assertSentCount(2);
});

it('resends the vendors from the school page', function () {
    Http::fake(['school.test/*' => Http::response(['synced' => 0])]);

    Livewire::test(EditSchool::class, ['record' => $this->school->id])
        ->callAction(TestAction::make('pushSmsVendors'))
        ->assertNotified('Al Helal Academy: 0 টি SMS Vendor সেট হয়েছে');

    Http::assertSentCount(1);
});

it('explains why the school did not take the vendors', function () {
    Http::fake(['school.test/*' => Http::response([], 422)]);

    $exception = rescue(fn () => app(PushSchoolSmsVendorsService::class)->handle($this->school), fn (Throwable $e) => $e, report: false);

    expect(PushSchoolSmsVendorsService::describeFailure($exception))->toContain('CENTRAL_SECRET');
});
