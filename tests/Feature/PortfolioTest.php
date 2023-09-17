<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:15:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

/** @noinspection PhpParamsInspection */

use App\Actions\Organisation\CRM\Customer\StoreCustomer;
use App\Actions\Organisation\Market\Shop\StoreShop;
use App\Actions\Organisation\Organisation\StoreOrganisation;
use App\Actions\Tenancy\Tenant\StoreTenant;
use App\Actions\Tenant\Portfolio\Banner\DeleteBanner;
use App\Actions\Tenant\Portfolio\Banner\StoreBanner;
use App\Actions\Tenant\Portfolio\Banner\UpdateBanner;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Enums\Organisation\Market\Shop\ShopTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Organisation\Organisation;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;

beforeAll(function () {
    loadDB('test_base_database.dump');
});

beforeEach(function () {
    try {
        $organisation = organisation();
    } catch (Exception) {
        $organisation = StoreOrganisation::make()->action(Organisation::factory()->definition());
        StoreShop::run(
            $organisation,
            [
                'type' => ShopTypeEnum::DIGITAL_MARKETING->value
            ]
        );
    }
    $tenant = Tenant::first();
    if (!$tenant) {
        $customer  = StoreCustomer::make()->action($organisation->shops->first(), Customer::factory()->definition());
        $modelData = Tenant::factory()->definition();
        $tenant    = StoreTenant::make()->action($customer, $modelData);
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
    $tenant = app('currentTenant');

    $modelData = Banner::factory()->definition();

    $banner = StoreBanner::make()->action($website, $modelData);
    $tenant->refresh();
    expect($banner)->toBeInstanceOf(Banner::class)
        ->and($tenant->portfolioStats->number_banners)->toBe(1)
        ->and($website->stats->number_banners)->toBe(1)
        ->and($tenant->portfolioStats->number_banners_state_unpublished)->toBe(1)
        ->and($tenant->portfolioStats->number_banners_state_live)->toBe(0)
        ->and($tenant->portfolioStats->number_banners_state_retired)->toBe(0);

    $this->artisan("banner:create abc test1 'My first banner' web1 ")->assertExitCode(0);

    // without website
    $this->artisan("banner:create abc test2 'My first banner'")->assertExitCode(0);

    $tenant->refresh();
    $website->fresh();
    expect($tenant->portfolioStats->number_banners)->toBe(3);

    return $banner;
})->depends('create websites');

test('update banner', function ($banner) {
    $modelData = Banner::factory()->definition();
    $banner    = UpdateBanner::make()->action($banner, $modelData);
    expect($banner)->toBeInstanceOf(Banner::class);
})->depends('create banners');

test('delete banner', function ($banner) {
    $tenant = app('currentTenant');
    DeleteBanner::make()->action($banner);
    expect($tenant->portfolioStats->number_banners)->toBe(2);
})->depends('create banners');
