<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 15:26:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Organisation\Guest\StoreGuest;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Enums\UI\Organisation\OrganisationUsersTabsEnum;
use App\Models\Auth\Guest;

use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\{get};
use function Pest\Laravel\{actingAs};

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
    actingAs($this->organisationUser, 'org');
});


test('create guest', function () {
    $organisationUserData = array_merge(
        Guest::factory()->definition(),
        [
            'positions' => ['cus-m'],
            'type'      => GuestTypeEnum::EXTERNAL_EMPLOYEE->value
        ]
    );


    $guest = StoreGuest::make()->action($organisationUserData);

    expect($guest)->toBeInstanceOf(Guest::class)
        ->and(organisation()->stats->number_organisation_users)->toBe(2)
        ->and(organisation()->stats->number_organisation_users_status_active)->toBe(2)
        ->and(organisation()->stats->number_guests)->toBe(2)
        ->and(organisation()->stats->number_guests_status_active)->toBe(2);

    return $guest;
});


test('can show sysadmin dashboard', function () {
    $response = get(route('org.sysadmin.dashboard'));

    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('SysAdmin/SysAdminDashboard')
            ->has('title')
            ->has('breadcrumbs', 2)
            ->where('stats.0.stat', 2)->where('stats.0.href.name', 'org.sysadmin.users.index')
            ->where('stats.1.stat', 2)->where('stats.1.href.name', 'org.sysadmin.guests.index');
    });
});

test('can show list of guests', function () {
    $response = get(route('org.sysadmin.guests.index'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('SysAdmin/Guests')
            ->has('title')
            ->has('breadcrumbs', 3)
            ->has('data.data', 2);
    });
});

test('can show guest', function () {
    $guest    = Guest::first();
    $response = get(route('org.sysadmin.guests.show', [$guest->slug]));

    $response->assertInertia(function (AssertableInertia $page) use ($guest) {
        $page
            ->component('SysAdmin/Guest')
            ->has('breadcrumbs', 3)
            ->has('tabs.navigation', 3);
    });
});


test('can show list of organisation users', function () {
    $response = get(route('org.sysadmin.users.index'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('SysAdmin/OrganisationUsers')
            ->has('title')
            ->has('breadcrumbs', 3)
            ->has(OrganisationUsersTabsEnum::USERS->value.'.data', 2);
    });
});

test('can show profile', function () {
    $response = get(route('org.profile.show'));
    $response->assertInertia(function (AssertableInertia $page) {
        $page
            ->component('EditModel')
            ->where('title', 'profile')
            ->has('breadcrumbs', 2)
            ->has('formData.blueprint', 3);
    });
});
