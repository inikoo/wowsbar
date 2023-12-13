<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Accounting\Billing\StoreBilling;
use App\Actions\Auth\CustomerUser\UpdateCustomerUser;
use App\Actions\Auth\User\StoreUser;
use App\Actions\Media\ImageGenerator;
use App\Actions\Portfolio\Banner\DeleteBanner;
use App\Actions\Portfolio\Banner\FetchFirebaseSnapshot;
use App\Actions\Portfolio\Banner\PublishBanner;
use App\Actions\Portfolio\Banner\StoreBanner;
use App\Actions\Portfolio\Banner\UpdateBanner;
use App\Actions\Portfolio\Banner\UpdateBannerState;
use App\Actions\Portfolio\Banner\UploadImagesToBanner;
use App\Actions\Portfolio\Gallery\UpdateUploadedImage;
use App\Actions\Portfolio\Gallery\UploadImagesToGallery;
use App\Actions\Portfolio\PortfolioDivision\SyncDivisionPortfolioWebsite;
use App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountAds\StorePortfolioSocialAccountAds;
use App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost\StorePortfolioSocialAccountPost;
use App\Actions\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPost\UpdatePortfolioSocialAccountPost;
use App\Actions\Portfolio\PortfolioSocialAccount\StorePortfolioSocialAccount;
use App\Actions\Portfolio\PortfolioSocialAccount\UpdatePortfolioSocialAccount;
use App\Actions\Portfolio\PortfolioWebsite\DeletePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UpdatePortfolioWebsite;
use App\Actions\UI\Customer\Profile\UpdateProfile;

Route::post('/portfolio-social-account/{portfolioSocialAccount}/ads', StorePortfolioSocialAccountAds::class)->name('portfolio-social-account.ads.store');
Route::post('/portfolio-social-account/{portfolioSocialAccount}/post', StorePortfolioSocialAccountPost::class)->name('portfolio-social-account.post.store');
Route::patch('/portfolio-social-account/{portfolioSocialAccount}/post/{post}', UpdatePortfolioSocialAccountPost::class)->name('portfolio-social-account.post.update');

Route::post('/portfolio-social-account', StorePortfolioSocialAccount::class)->name('portfolio-social-account.store');
Route::patch('/portfolio-social-account/{portfolioSocialAccount}', UpdatePortfolioSocialAccount::class)->name('portfolio-social-account.update');

Route::prefix('portfolio-website')->name('portfolio-website.')->group(function () {
    Route::post('', StorePortfolioWebsite::class)->name('store');
    Route::post('/from-welcome', [StorePortfolioWebsite::class, 'fromWelcome'])->name('store.from-welcome');

    Route::prefix('{portfolioWebsite:id}')->group(function () {
        Route::patch('', UpdatePortfolioWebsite::class)->name('update');
        Route::delete('', DeletePortfolioWebsite::class)->name('delete');
        Route::post('banner', [StoreBanner::class, 'inPortfolioWebsite'])->name('banner.store');
        Route::post('interest', SyncDivisionPortfolioWebsite::class)->name('interest.store');
    });
});

Route::prefix('/banner')->name('banner.')->group(function () {
    Route::post('', [StoreBanner::class, 'inCustomer'])->name('store');
    Route::post('from-gallery', [StoreBanner::class, 'fromGallery'])->name('store.from-gallery');

    Route::prefix('{banner:id}')->group(function () {
        Route::patch('', UpdateBanner::class)->name('update');
        Route::patch('publish', PublishBanner::class)->name('publish');
        Route::patch('fetch-firebase', FetchFirebaseSnapshot::class)->name('fetch-firebase');
        Route::post('images', UploadImagesToBanner::class)->name('images.store');
        Route::patch('state/{state}', UpdateBannerState::class)->name('update-state');
        Route::delete('', DeleteBanner::class)->name('delete');
        Route::patch('shutdown', PublishBanner::class)->name('shutdown');
        Route::patch('switch-on', PublishBanner::class)->name('switch-on');

    });
});

Route::patch('/images/{media}', UpdateUploadedImage::class)->name('images.update');

Route::post('/user', StoreUser::class)->name('user.store');
Route::patch('/user/{customerUser:id}', UpdateCustomerUser::class)->name('user.update');
Route::patch('/profile', UpdateProfile::class)->name('profile.update');

//Route::patch('/system-settings', UpdateSystemSettings::class)->name('system-settings.update');
Route::post('/generator', ImageGenerator::class)->name('image.generate');




Route::post('/gallery/images/upload', UploadImagesToGallery::class)->name('gallery.images.upload');

Route::post('/billing', StoreBilling::class)->name('billing.store');
