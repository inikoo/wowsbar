<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\HumanResources\Workplace\StoreWorkplace;
use App\Actions\UI\Organisation\Profile\GetProfileAppLoginQRCode;

use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Models\Helpers\Address;
use App\Models\HumanResources\Workplace;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{actingAs};

use function Pest\Laravel\{getJson};
use function Pest\Laravel\{postJson};

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(function () {
    list(
        $this->organisation,
        $this->organisationUser,
        $this->shop
    ) = createShop();

    Config::set(
        'inertia.testing.page_paths',
        [resource_path('js/Pages/Organisation')]
    );
    $this->qrCode = '01HK72GNDME2AZMHA9T7N9WF1A';


    if (!Workplace::where('name', 'office')->exists()) {
        $modelData = [
            'name'    => 'office',
            'type'    => WorkplaceTypeEnum::BRANCH,
            'address' => Address::factory()->definition()
        ];

        StoreWorkplace::make()->action($modelData);
    }

    $this->workplace = Workplace::where('name', 'office')->first();
});


test('create qr code', function () {
    actingAs($this->organisationUser, 'org');

    GetProfileAppLoginQRCode::partialMock()
        ->shouldReceive('getCode')
        ->andReturn($this->qrCode);

    $response = getJson(route('org.models.profile.app-login-qrcode'));
    $response->assertOk();
    $response->assertJson([
        'code' => $this->qrCode
    ]);
});

test('create api token from qr code', function () {
    Cache::shouldReceive('get')
        ->once()
        ->with('profile-app-qr-code:'.$this->qrCode)
        ->andReturn($this->organisationUser->id);
    Cache::shouldReceive('forget')
        ->once()
        ->with('profile-app-qr-code:'.$this->qrCode);

    $response = postJson(route('mobile-app.tokens.qr-code.store', [
        'code'        => $this->qrCode,
        'device_name' => 'test device'
    ]));
    $response->assertOk();
    $response->assertJsonStructure([
        'token'
    ]);
})->depends('create qr code');

test('create api token from credentials', function () {
    $response = postJson(route('mobile-app.tokens.credentials.store', [
        'username'    => $this->organisationUser->username,
        'password'    => 'password',
        'device_name' => 'test device'
    ]));
    $response->assertOk();
    $response->assertJsonStructure([
        'token'
    ]);
});

test('get profile data', function () {
    Sanctum::actingAs(
        $this->organisationUser,
        ['*']
    );
    $response = getJson(route('mobile-app.profile.show'));
    $response->assertOk();
    expect($response->json('data'))->toBeArray()
        ->and($response->json('data'))
        ->id->toBe($this->organisationUser->id)
        ->contact_name->toBe($this->organisationUser->contact_name)
        ->username->toBe($this->organisationUser->username)
        ->roles->toBeArray()
        ->permissions->toBeArray();
});

test('get clocking machines list', function () {
    Sanctum::actingAs(
        $this->organisationUser,
        ['*']
    );
    $response = getJson(route('mobile-app.hr.clocking-machines.index'));

    $response->assertOk();
    expect($response->json('data'))->toBeArray()
        ->and($response->json('data'))
        ->toHaveCount(0);
});


test('get working places list', function () {
    Sanctum::actingAs(
        $this->organisationUser,
        ['*']
    );
    $response = getJson(route('mobile-app.hr.workplaces.index'));

    $response->assertOk();
    expect($response->json('data'))->toBeArray()
        ->and($response->json('data'))
        ->toHaveCount(1);
});

test('get working place data', function () {
    Sanctum::actingAs(
        $this->organisationUser,
        ['*']
    );
    $response = getJson(route('mobile-app.hr.workplaces.show', [$this->workplace->id]));
    $response->assertOk();

    expect($response->json('data'))->toBeArray()
        ->and($response->json('data'))
        ->id->toBe($this->workplace->id)
        ->name->toBe($this->workplace->name);
});

test('get clocking machines list in workplace', function () {
    Sanctum::actingAs(
        $this->organisationUser,
        ['*']
    );
    $response = getJson(route('mobile-app.hr.workplaces.show.clocking-machines.index', [$this->workplace->id]));

    $response->assertOk();
    expect($response->json('data'))->toBeArray()
        ->and($response->json('data'))
        ->toHaveCount(0);
});
