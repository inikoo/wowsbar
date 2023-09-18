<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Tue, 14 Mar 2023 10:25:11 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */


use App\Actions\Auth\User\StoreUser;
use App\Actions\Auth\User\UpdateUser;
use App\Actions\Media\ImageGenerator;
use App\Actions\Portfolio\Banner\DeleteBanner;
use App\Actions\Portfolio\Banner\FetchFirebaseSnapshot;
use App\Actions\Portfolio\Banner\PublishBanner;
use App\Actions\Portfolio\Banner\StoreBanner;
use App\Actions\Portfolio\Banner\UpdateBanner;
use App\Actions\Portfolio\Banner\UpdateBannerState;
use App\Actions\Portfolio\Gallery\UpdateUploadedImage;
use App\Actions\Portfolio\PortfolioWebsite\DeletePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UpdatePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UploadPortfolioWebsite;
use App\Actions\Tenancy\Tenant\UpdateSystemSettings;
use App\Actions\UI\Authenticated\Profile\UpdateProfile;

Route::post('/portfolio-website', StorePortfolioWebsite::class)->name('portfolio-website.store');
Route::patch('/portfolio-website/{portfolioWebsite}', UpdatePortfolioWebsite::class)->name('portfolio-website.update');
Route::delete('/portfolio-website/{portfolioWebsite}', DeletePortfolioWebsite::class)->name('portfolio-website.delete');

Route::post('/portfolio-website/{portfolioWebsite}/banners', [StoreBanner::class, 'inPortfolioWebsite'])->name('portfolio-website.banner.store');
Route::post('/portfolio-website/{portfolioWebsite}/banners/gallery', [StoreBanner::class, 'inPortfolioWebsiteFromGallery'])->name('portfolio-website.banner.gallery.store');

Route::post('/tenant/banners/gallery', [StoreBanner::class, 'inTenantFromGallery'])->name('tenant.banner.gallery.store');

Route::post('/banner', [StoreBanner::class, 'inCustomer'])->name('banner.store');
Route::patch('/banner/{banner}', UpdateBanner::class)->name('banner.update');
Route::patch('/banner/{banner}/publish', PublishBanner::class)->name('banner.publish');
Route::patch('/banner/{banner}/fetch-firebase', FetchFirebaseSnapshot::class)->name('banner.fetch-firebase');

Route::patch('/banner/{banner}/state/{state}', UpdateBannerState::class)->name('banner.update-state');
Route::delete('/banner/{banner}', DeleteBanner::class)->name('content-block.delete');

Route::patch('/images/{media}', UpdateUploadedImage::class)->name('images.update');

Route::post('/user', StoreUser::class)->name('user.store');

Route::patch('/user/{user:username}', UpdateUser::class)->name('user.update');
Route::patch('/profile', UpdateProfile::class)->name('profile.update');

Route::patch('/system-settings', UpdateSystemSettings::class)->name('system-settings.update');
Route::post('/generator', ImageGenerator::class)->name('image.generate');

Route::post('/portfolio-websites/imports/upload', UploadPortfolioWebsite::class)->name('websites.upload');
