<?php
/** @noinspection PhpParamsInspection */

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Auth\User\StoreUser;
use App\Actions\Auth\User\UpdateUser;
use App\Actions\Auth\User\UpdateUserStatus;
use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Models\Auth\User;
use App\Models\Tenancy\Tenant;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(function () {
    $tenant = Tenant::first();
    if (!$tenant) {
        $modelData = Tenant::factory()->definition();
        $tenant    = StoreTenant::make()->action($modelData);
        $modelData = array_merge(
            Tenant::factory()->definition(),
            [
                'code'     => 'XYZ',
                'username' => 'xyz',
            ]
        );
        StoreTenant::make()->action($modelData);
    }
    $tenant->makeCurrent();
});

test('create user', function () {
    $tenant = app('currentTenant');

    $storeUser = StoreUser::make()->action($tenant, User::factory()->definition());
    expect($storeUser)->toBeInstanceOf(User::class);

    return $storeUser;
});

test('update user', function ($user) {
    $storeUser = UpdateUser::make()->action($user, User::factory()->definition());

    expect($storeUser)->toBeInstanceOf(User::class)
        ->and($storeUser->wasChanged())->toBeTrue();
})->depends('create user');

test('deactivate a user', function ($user) {
    $user = UpdateUserStatus::make()->action($user, false );

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->status)->toBeFalse();
})->depends('create user');
