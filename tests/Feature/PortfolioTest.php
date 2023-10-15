<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:15:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

/** @noinspection PhpParamsInspection */

use App\Actions\Portfolio\Banner\DeleteBanner;
use App\Actions\Portfolio\Banner\StoreBanner;
use App\Actions\Portfolio\Banner\UpdateBanner;
use App\Actions\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;

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

test('create portfolio websites', function () {
    $customer  = customer();
    $modelData = PortfolioWebsite::factory()->definition();

    $website   = StorePortfolioWebsite::make()->action($customer, $modelData);
    $customer->refresh();
    expect($website)->toBeInstanceOf(PortfolioWebsite::class)
        ->and($customer->portfolioStats->number_portfolio_websites)->toBe(1);
    $modelData = PortfolioWebsite::factory()->definition();
    $website2  = StorePortfolioWebsite::make()->action($customer, $modelData);
    $customer->refresh();
    expect($website2)->toBeInstanceOf(PortfolioWebsite::class)
        ->and($customer->portfolioStats->number_portfolio_websites)->toBe(2);

    $this->artisan("customer:new-portfolio-website $customer->slug https://hello-world.com  Hello")->assertExitCode(0);
    $customer->refresh();
    expect($customer->portfolioStats->number_portfolio_websites)->toBe(3);

    return $website;
});


test('create banners', function ($website) {
    $customer = customer();

    $modelData = Banner::factory()->definition();

    $banner = StoreBanner::make()->action($website, $modelData);
    $customer->refresh();
    expect($banner)->toBeInstanceOf(Banner::class)
        ->and($customer->portfolioStats->number_banners)->toBe(1)
        ->and($website->stats->number_banners)->toBe(1)
        ->and($customer->portfolioStats->number_banners_state_unpublished)->toBe(1)
        ->and($customer->portfolioStats->number_banners_state_live)->toBe(0)
        ->and($customer->portfolioStats->number_banners_state_switch_off)->toBe(0);

    $this->artisan("customer:new-banner $customer->slug  $website->slug -N 'My first banner' ")->assertExitCode(0);
    $this->artisan("customer:new-banner $customer->slug  $website->slug -N 'My second banner' ")->assertExitCode(0);

    $customer->refresh();
    $website->fresh();
    expect($customer->portfolioStats->number_banners)->toBe(3);

    return $banner;
})->depends('create portfolio websites');

test('update banner', function ($banner) {
    $modelData = Banner::factory()->definition();
    $banner    = UpdateBanner::make()->action($banner, $modelData);
    expect($banner)->toBeInstanceOf(Banner::class);
})->depends('create banners');

test('delete banner', function ($banner) {
    $customer = customer();
    DeleteBanner::make()->action($customer, $banner);
    expect($customer->portfolioStats->number_banners)->toBe(2);
})->depends('create banners');
