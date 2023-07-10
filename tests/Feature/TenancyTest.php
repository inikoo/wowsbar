<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Models\Tenancy\Tenant;
use App\Models\Tenancy\User;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

test('create tenant', function () {
    $modelData = Tenant::factory()->definition();
    $tenant    = StoreTenant::make()->action($modelData);
    expect($tenant)->toBeInstanceOf(Tenant::class);
    /** @var User $user */
    $user = $tenant->users()->first();
    expect($user)->toBeInstanceOf(User::class)
        ->and($user->is_root)->toBeTrue();

    return $tenant;
});
