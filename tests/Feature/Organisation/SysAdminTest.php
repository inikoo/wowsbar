<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 15:26:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Organisation\Guest\StoreGuest;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Auth\Guest;

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
