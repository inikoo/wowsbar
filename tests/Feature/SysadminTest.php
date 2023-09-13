<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Organisation\CRM\Customer\StoreCustomer;
use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Actions\Tenant\Auth\User\StoreUser;
use App\Actions\Tenant\Auth\User\UpdateUser;
use App\Actions\Tenant\Auth\User\UpdateUserStatus;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\Organisation\Organisation;
use App\Models\Tenancy\Tenant;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(function () {
    try {
        $organisation=organisation();
    } catch (Exception) {
        $organisation = StoreOrganisation::make()->action(Organisation::factory()->definition());
    }
    $tenant = Tenant::first();
    if (!$tenant) {
        $customer  = StoreCustomer::make()->action($organisation, Customer::factory()->definition());
        $modelData = Tenant::factory()->definition();
        $tenant    = StoreTenant::make()->action($customer, $modelData);
    }
    $tenant->makeCurrent();
});

test('create user', function () {
    $tenant    = app('currentTenant');
    $storeUser = StoreUser::make()->action($tenant, User::factory()->definition());
    expect($storeUser)->toBeInstanceOf(User::class)
        ->and($tenant->stats->number_users)->toBe(1)
        ->and($tenant->stats->number_users_status_active)->toBe(1)
        ->and($tenant->stats->number_users_status_inactive)->toBe(0);

    return $storeUser;
});

test('update user', function ($user) {
    $storeUser = UpdateUser::make()->action($user, User::factory()->definition());
    expect($storeUser)->toBeInstanceOf(User::class)
        ->and($storeUser->wasChanged())->toBeTrue();
})->depends('create user');

test('deactivate a user', function ($user) {
    $tenant    = app('currentTenant');
    $user      = UpdateUserStatus::make()->action($user, false);
    expect($user)->toBeInstanceOf(User::class)
        ->and($user->status)->toBeFalse()
        ->and($tenant->stats->number_users)->toBe(1)
        ->and($tenant->stats->number_users_status_active)->toBe(0)
        ->and($tenant->stats->number_users_status_inactive)->toBe(1);
})->depends('create user');
