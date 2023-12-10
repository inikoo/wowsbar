<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 15:26:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Helpers\Avatars\GetDiceBearAvatar;
use App\Actions\SysAdmin\Guest\DeleteGuest;
use App\Actions\SysAdmin\Guest\StoreGuest;
use App\Actions\SysAdmin\Guest\UpdateGuest;
use App\Actions\SysAdmin\Organisation\StoreOrganisation;
use App\Actions\SysAdmin\OrganisationUser\UpdateOrganisationUser;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Enums\UI\Organisation\OrganisationUsersTabsEnum;
use App\Models\Auth\Guest;

use App\Models\Auth\OrganisationUser;
use App\Models\SysAdmin\Organisation;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\{get};
use function Pest\Laravel\{actingAs};

beforeAll(function () {
    loadDB('test_base_database.dump');
});


beforeEach(function () {
    GetDiceBearAvatar::mock()
        ->shouldReceive('handle')
        ->andReturn(Storage::disk('art')->get('avatars/shapes.svg'));

    Config::set(
        'inertia.testing.page_paths',
        [resource_path('js/Pages/Organisation')]
    );
});

test('create organisation', function () {
    $modelData = Organisation::factory()->definition();
    data_set($modelData, 'code', 'acme');
    $organisation = StoreOrganisation::make()->action($modelData);
    expect($organisation)->toBeInstanceOf(Organisation::class);

    return $organisation;
});

test('fail to create guest with invalid usernames', function () {
    $guestData = Guest::factory()->definition();

    data_set($guestData, 'username', 'create');

    expect(function () use ($guestData) {
        StoreGuest::make()->action(
            $guestData
        );
    })->toThrow(ValidationException::class);

    data_set($guestData, 'username', 'export');
    expect(function () use ($guestData) {
        StoreGuest::make()->action(
            $guestData
        );
    })->toThrow(ValidationException::class);
});


test('create guest from command', function () {
    $this->artisan(
        'guest:create',
        [
            'type'       => 'external_administrator',
            'name'       => 'Mr Pika',
            'alias'      => 'pika',
            '--password' => 'hello1234',
            '--email'    => 'pika@inikoo.com',

        ]
    )->assertSuccessful();

    /** @var Guest $guest */
    $guest = Guest::where('alias', 'pika')->firstOrFail();

    expect($guest->organisationUser->username)->toBe('pika')
        ->and(organisation()->stats->number_organisation_users)->toBe(1)
        ->and(organisation()->stats->number_organisation_users_status_active)->toBe(1)
        ->and(organisation()->stats->number_guests)->toBe(1)
        ->and(organisation()->stats->number_guests_status_active)->toBe(1);

    return $guest;
});

test('update guest', function ($guest) {
    $guest = UpdateGuest::make()->action($guest, ['contact_name' => 'Wow']);
    expect($guest->contact_name)->toBe('Wow');

    $guest = UpdateGuest::make()->action($guest, ['contact_name' => 'John']);
    expect($guest->contact_name)->toBe('John');

    return $guest;
})->depends('create guest from command');

test('update organisation user password', function (Guest $guest) {
    Hash::shouldReceive('make')
        ->andReturn('hello1234');
    /** @noinspection PhpUndefinedMethodInspection */
    Hash::makePartial();

    $organisationUser = UpdateOrganisationUser::make()->action($guest->organisationUser, [
        'password' => 'secret'
    ]);

    expect($organisationUser->password)->toBe('hello1234');

    return $organisationUser;
})->depends('update guest');

test('update organisation user username', function (OrganisationUser $organisationUser) {
    expect($organisationUser->username)->toBe('pika');
    $organisationUser = UpdateOrganisationUser::make()->action($organisationUser, [
        'username' => 'new-username'
    ]);

    expect($organisationUser->username)->toBe('new-username')
        ->and($organisationUser->status)->toBeTrue();

    return $organisationUser;
})->depends('update organisation user password');

test('create guest', function () {
    $guessData = array_merge(
        Guest::factory()->definition(),
        [
            'password'  => 'secret-password',
            'positions' => ['admin'],
            'type'      => GuestTypeEnum::EXTERNAL_EMPLOYEE->value
        ]
    );


    $guest = StoreGuest::make()->action($guessData);

    expect($guest)->toBeInstanceOf(Guest::class)
        ->and(organisation()->stats->number_organisation_users)->toBe(2)
        ->and(organisation()->stats->number_organisation_users_status_active)->toBe(2)
        ->and(organisation()->stats->number_guests)->toBe(2)
        ->and(organisation()->stats->number_guests_status_active)->toBe(2);


    return $guest;
});


test('delete guest', function (OrganisationUser $organisationUser) {
    /** @var Guest $guest */
    $guest = $organisationUser->parent;
    $guest = DeleteGuest::make()->action($guest);
    expect($guest->deleted_at)->toBeInstanceOf(Carbon::class)
        ->and(organisation()->stats->number_organisation_users)->toBe(1)
        ->and(organisation()->stats->number_organisation_users_status_active)->toBe(1)
        ->and(organisation()->stats->number_guests)->toBe(1)
        ->and(organisation()->stats->number_guests_status_active)->toBe(1);
})->depends('update organisation user username');

test('create another guest', function () {
    $guessData = array_merge(
        Guest::factory()->definition(),
        [
            'positions' => ['hr-m'],
            'type'      => GuestTypeEnum::EXTERNAL_EMPLOYEE->value
        ]
    );


    $guest = StoreGuest::make()->action($guessData);

    expect($guest)->toBeInstanceOf(Guest::class)
        ->and(organisation()->stats->number_organisation_users)->toBe(2)
        ->and(organisation()->stats->number_organisation_users_status_active)->toBe(2)
        ->and(organisation()->stats->number_guests)->toBe(2)
        ->and(organisation()->stats->number_guests_status_active)->toBe(2);

    return $guest;
});

test('can show app login', function () {
    $response = get(route('org.login'));

    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Auth/Login');
    });
});

test('can not login with wrong credentials', function (Guest $guest) {
    $response = $this->post(route('org.login.store'), [
        'username' => $guest->organisationUser->username,
        'password' => 'wrong password',
    ]);

    $response->assertRedirect('http://'.config('app.domain'));
    $response->assertSessionHasErrors('username');

    $organisationUser = $guest->organisationUser;
    $organisationUser->refresh();
    expect($organisationUser->stats->number_failed_logins)->toBe(1);
})->depends('create guest');

test('can login', function (Guest $guest) {
    $response = $this->post(route('org.login.store'), [
        'username' => $guest->organisationUser->username,
        'password' => 'secret-password',
    ]);
    $response->assertRedirect(route('org.dashboard.show'));

    $organisationUser = $guest->organisationUser;
    $organisationUser->refresh();
    expect($organisationUser->stats->number_logins)->toBe(1);
})->depends('create guest');

test('can show sysadmin dashboard', function (Guest $guest) {
    actingAs($guest->organisationUser, 'org');
    $response = get(route('org.sysadmin.dashboard'));

    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('SysAdmin/SysAdminDashboard')
            ->has('title')
            ->has('breadcrumbs', 2)
            ->where('stats.0.stat', 2)->where('stats.0.href.name', 'org.sysadmin.users.index')
            ->where('stats.1.stat', 2)->where('stats.1.href.name', 'org.sysadmin.guests.index');
    });
})->depends('create guest');

test('can show list of guests', function (Guest $guest) {
    actingAs($guest->organisationUser, 'org');
    $response = get(route('org.sysadmin.guests.index'));

    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('SysAdmin/Guests')
            ->has('title')
            ->has('breadcrumbs', 3)
            ->has('data.data', 2);
    });
})->depends('create guest');

test('can show guest', function (Guest $guest) {
    actingAs($guest->organisationUser, 'org');
    $guest    = Guest::first();
    $response = get(route('org.sysadmin.guests.show', [$guest->slug]));

    $response->assertInertia(function (AssertableInertia $page) use ($guest) {
        $page
            ->component('SysAdmin/Guest')
            ->has('breadcrumbs', 3)
            ->has('tabs.navigation', 3);
    });
})->depends('create guest');


test('can show list of organisation users', function (Guest $guest) {
    actingAs($guest->organisationUser, 'org');
    $response = get(route('org.sysadmin.users.index'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('SysAdmin/OrganisationUsers')
            ->has('title')
            ->has('breadcrumbs', 3)
            ->has(OrganisationUsersTabsEnum::USERS->value.'.data', 2);
    });
})->depends('create guest');

test('can show profile', function (Guest $guest) {
    actingAs($guest->organisationUser, 'org');
    $response = get(route('org.profile.show'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('EditModel')
            ->where('title', 'profile')
            ->has('breadcrumbs', 2)
            ->has('formData.blueprint', 3);
    });
})->depends('create guest');
