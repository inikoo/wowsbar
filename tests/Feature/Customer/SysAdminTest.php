<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 15:11:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Auth\User\StoreUser;
use App\Actions\Auth\User\UpdateUser;
use App\Actions\Auth\User\SuspendUser;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\User;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(
    /**
     * @throws \Throwable
     */
    function () {

        list(
            $this->organisation,
            $this->organisationUser,
            $this->shop,
            $this->customer
        ) = createCustomer();
    }
);

test('create user', function () {
    $customer         = customer();
    $customerUser     = StoreUser::make()->action($customer, User::factory()->definition());
    $organisation     =organisation();
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

    return $customerUser->user;
});

test('update user', function ($user) {
    $user = UpdateUser::make()->action($user, User::factory()->definition());
    expect($user)->toBeInstanceOf(User::class)
        ->and($user->wasChanged())->toBeTrue();
})->depends('create user');

test('deactivate a user', function ($user) {
    $customer = customer();
    $user     = SuspendUser::make()->action($user);
    expect($user)->toBeInstanceOf(User::class)
        ->and($customer->stats->number_customer_users)->toBe(1)
        ->and($customer->stats->number_customer_users_status_active)->toBe(0)
        ->and($customer->stats->number_customer_users_status_inactive)->toBe(1);
})->depends('create user');
