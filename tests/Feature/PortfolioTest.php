<?php

/** @noinspection PhpParamsInspection */

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Portfolio\ContentBlock\StoreContentBlock;
use App\Actions\Portfolio\Website\StoreWebsite;
use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use App\Models\Tenancy\Tenant;
use App\Models\Web\WebBlockType;

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

test('create websites', function () {
    $tenant    = app('currentTenant');
    $modelData = Website::factory()->definition();
    $website   = StoreWebsite::make()->action(
        array_merge(
            $modelData,
            [
                'code' => 'web1'
            ]
        )
    );
    $tenant->refresh();
    expect($website)->toBeInstanceOf(Website::class)
        ->and($website->slug)->toBe('web1')
        ->and($tenant->stats->number_websites)->toBe(1);
    $modelData = Website::factory()->definition();
    $website2  = StoreWebsite::make()->action($modelData);
    $tenant->refresh();
    expect($website2)->toBeInstanceOf(Website::class)
        ->and($tenant->stats->number_websites)->toBe(2);

    $this->artisan('website:create abc hello.com HI Hello')->assertExitCode(0);
    $tenant->refresh();
    expect($tenant->stats->number_websites)->toBe(3);

    return $website;
});


test('create banners', function ($website) {
    $tenant = app('currentTenant');

    $webBlockType = WebBlockType::where('slug', 'banner')->first();
    $webBlock     = $webBlockType->webBlocks[0];
    $modelData    = ContentBlock::factory()->definition();

    $contentBlock = StoreContentBlock::make()->action($website, $webBlock, $modelData);
    expect($contentBlock)->toBeInstanceOf(ContentBlock::class)
        ->and($tenant->stats->number_content_blocks)->toBe(1);

    $this->artisan("content-block:create abc web1 banner test1 'My first banner' ")->assertExitCode(0);
    $tenant->refresh();
    expect($tenant->stats->number_content_blocks)->toBe(2);
})->depends('create websites');
