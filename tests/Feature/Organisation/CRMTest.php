<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\CRM\Customer\StoreCustomer;
use App\Actions\CRM\User\StoreOrgCustomerUser;
use App\Models\Auth\CustomerUser;
use App\Models\CRM\Customer;
use App\Models\CRM\CustomerStats;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;

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

test('test hydrators', function () {
    /** @var Organisation $organisation */
    $organisation = $this->organisation;
    expect($organisation->stats->number_shops)->toBe(1)
        ->and($organisation->stats->number_shops)->toBe(1)
        ->and($organisation->stats->number_websites)->toBe(1);
});


test('create customer', function () {
    $shop      = $this->shop;
    $modelData = Customer::factory()->definition();

    $customer = StoreCustomer::make()->action($shop, $modelData);
    expect($customer)->toBeInstanceOf(Customer::class)
        ->and($customer->stats)->toBeInstanceOf(CustomerStats::class)
        ->and($shop->crmStats->number_customers)->toBe(1)
        ->and(organisation()->crmStats->number_customers)->toBe(1);

    return $customer;
});

test('create customer user', function ($customer) {
    /** @var Shop $shop */
    $shop         = $this->shop;
    $customerUser = StoreOrgCustomerUser::make()->action(
        $customer,
        [
            'username'     => 'aiku',
            'contact_name' => 'Alex',
            'email'        => $customer->email,
            'password'     => fake()->password
        ]
    );
    $shop->refresh();
    $organisation = $this->organisation->refresh();
    $customer->refresh();
    expect($customerUser)->toBeInstanceOf(CustomerUser::class)
        ->and($organisation->crmStats->number_customer_users)->toBe(1)
        ->and($organisation->crmStats->number_customer_users_status_active)->toBe(1)
        ->and($organisation->crmStats->number_customer_users_status_inactive)->toBe(0)
        ->and($customer->shop->crmStats->number_customer_users)->toBe(1)
        ->and($customer->shop->crmStats->number_customer_users_status_active)->toBe(1)
        ->and($customer->shop->crmStats->number_customer_users_status_inactive)->toBe(0)
        ->and($customer->website->webStats->number_customer_users)->toBe(1)
        ->and($customer->website->webStats->number_customer_users_status_active)->toBe(1)
        ->and($customer->website->webStats->number_customer_users_status_inactive)->toBe(0)
        ->and($customer->stats->number_customer_users)->toBe(1)
        ->and($customer->stats->number_customer_users_status_active)->toBe(1)
        ->and($customer->stats->number_customer_users_status_inactive)->toBe(0);
})->depends('create customer');
