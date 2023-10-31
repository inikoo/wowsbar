<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
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
        createCustomer();
    }
);

test('create user', function () {
    $customer         = customer();
    $customerUser     = StoreUser::make()->action($customer->shop->website, $customer, User::factory()->definition());
    expect($customerUser)->toBeInstanceOf(CustomerUser::class)
        ->and($customer->stats->number_users)->toBe(1)
        ->and($customer->stats->number_users_status_active)->toBe(1)
        ->and($customer->stats->number_users_status_inactive)->toBe(0);

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
        ->and($customer->stats->number_users)->toBe(1)
        ->and($customer->stats->number_users_status_active)->toBe(0)
        ->and($customer->stats->number_users_status_inactive)->toBe(1);
})->depends('create user');
