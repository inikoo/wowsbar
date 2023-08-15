<?php

/** @noinspection PhpParamsInspection */

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 10:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Portfolio\ContentBlock\DeleteContentBlock;
use App\Actions\Portfolio\ContentBlock\StoreContentBlock;
use App\Actions\Portfolio\ContentBlock\UpdateContentBlock;
use App\Actions\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\PortfolioWebsite;
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
    $modelData = PortfolioWebsite::factory()->definition();
    $website   = StorePortfolioWebsite::make()->action(
        array_merge(
            $modelData,
            [
                'code' => 'web1'
            ]
        )
    );
    $tenant->refresh();
    expect($website)->toBeInstanceOf(PortfolioWebsite::class)
        ->and($website->slug)->toBe('web1')
        ->and($tenant->stats->number_websites)->toBe(1);
    $modelData = PortfolioWebsite::factory()->definition();
    $website2  = StorePortfolioWebsite::make()->action($modelData);
    $tenant->refresh();
    expect($website2)->toBeInstanceOf(PortfolioWebsite::class)
        ->and($tenant->stats->number_websites)->toBe(2);

    $this->artisan('website:create abc hello.com HI Hello')->assertExitCode(0);
    $tenant->refresh();
    expect($tenant->stats->number_websites)->toBe(3);

    return $website;
});


test('create banners', function ($website) {
    $tenant       = app('currentTenant');
    $webBlockType = WebBlockType::where('slug', 'banner')->first();
    $webBlock     = $webBlockType->webBlocks[0];
    $modelData    = ContentBlock::factory()->definition();

    $contentBlock = StoreContentBlock::make()->action($website, $webBlock, $modelData);
    $tenant->refresh();
    expect($contentBlock)->toBeInstanceOf(ContentBlock::class)
        ->and($tenant->contentBlockStats->number_content_blocks)->toBe(1);

    $this->artisan("content-block:create abc web1 banner test1 'My first banner' ")->assertExitCode(0);
    $tenant->refresh();
    expect($tenant->contentBlockStats->number_content_blocks)->toBe(2);

    return $contentBlock;
})->depends('create websites');

test('update banner', function ($contentBlock) {
    $modelData    = ContentBlock::factory()->definition();

    $contentBlock = UpdateContentBlock::make()->action($contentBlock, $modelData);
    expect($contentBlock)->toBeInstanceOf(ContentBlock::class);
})->depends('create banners');

test('delete banner', function ($contentBlock) {
    $tenant = app('currentTenant');

    DeleteContentBlock::make()->action($contentBlock);
    expect($tenant->contentBlockStats->number_content_blocks)->toBe(1);
})->depends('create banners');
