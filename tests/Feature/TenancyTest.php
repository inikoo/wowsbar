<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Models\Auth\User;
use App\Models\Tenancy\Tenant;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

test('create tenant', function () {
    $modelData = Tenant::factory()->definition();
    $tenant    = StoreTenant::make()->action($modelData);
    expect($tenant)->toBeInstanceOf(Tenant::class);
    $tenant->makeCurrent();
    /** @var \App\Models\Auth\User $user */
    $user = $tenant->users()->first();
    expect($user)->toBeInstanceOf(User::class)
        ->and($user->is_public)->toBeTrue()
        ->and($user->getMedia('avatar')->first())->toBeInstanceOf(Media::class)
        ->and($user->avatar)->toBeInstanceOf(App\Models\Media\Media::class);

});

test('create tenant command', function () {
    $this->artisan('tenant:create aiku devels@aw-advantage.com Devs aiku hello GB GBP')->assertExitCode(0);
    expect(Tenant::count())->toBe(2);

});
