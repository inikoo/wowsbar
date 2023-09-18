<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:15:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

/** @noinspection PhpParamsInspection */

use App\Actions\Tenant\Portfolio\Banner\DeleteBanner;
use App\Actions\Tenant\Portfolio\Banner\StoreBanner;
use App\Actions\Tenant\Portfolio\Banner\UpdateBanner;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
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
    $website   = StorePortfolioWebsite::make()->action(
        array_merge(
            $modelData,
            [
                'code' => 'web1'
            ]
        )
    );
    $customer->refresh();
    expect($website)->toBeInstanceOf(PortfolioWebsite::class)
        ->and($website->slug)->toBe('web1')
        ->and($customer->stats->number_portfolio_websites)->toBe(1);
    $modelData = PortfolioWebsite::factory()->definition();
    $website2  = StorePortfolioWebsite::make()->action($modelData);
    $customer->refresh();
    expect($website2)->toBeInstanceOf(PortfolioWebsite::class)
        ->and($customer->stats->number_portfolio_websites)->toBe(2);

    $this->artisan("customer:new-portfolio-website {$customer->slug} hello.com HI Hello")->assertExitCode(0);
    $customer->refresh();
    expect($customer->stats->number_portfolio_websites)->toBe(3);

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
        ->and($customer->portfolioStats->number_banners_state_retired)->toBe(0);

    $this->artisan("customer:new-banner {$customer->slug} test1 'My first banner' web1 ")->assertExitCode(0);

    // without website
    $this->artisan("customer:new-banner {$customer->slug} test2 'My first banner'")->assertExitCode(0);

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
    DeleteBanner::make()->action($banner);
    expect($customer->portfolioStats->number_banners)->toBe(2);
})->depends('create banners');
