<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Portfolio\Website\StoreWebsite;
use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Models\Portfolio\Website;
use App\Models\Tenancy\Tenant;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(function () {
    $tenant = Tenant::first();
    if (!$tenant) {
        $modelData = Tenant::factory()->definition();
        $tenant=StoreTenant::make()->action($modelData);
        $modelData = array_merge(
            Tenant::factory()->definition(),
            [
                'code' => 'XYZ',
                'username' => 'xyz',
            ]
        );
        StoreTenant::make()->action($modelData);
    }
    $tenant->makeCurrent();
});

test('create websites', function () {
    $tenant=app('currentTenant');
    $modelData = Website::factory()->definition();
    $website=StoreWebsite::make()->action($modelData);
    expect($website)->toBeInstanceOf(Website::class)
        ->and($tenant->stats->number_websites)->toBe(1);
    $modelData = Website::factory()->definition();
    $website2=StoreWebsite::make()->action($modelData);
    $tenant->refresh();
    expect($website2)->toBeInstanceOf(Website::class)
        ->and($tenant->stats->number_websites)->toBe(2);
    return $website;
});
