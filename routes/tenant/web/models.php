<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Tue, 14 Mar 2023 10:25:11 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */


use App\Actions\Media\ImageGenerator;
use App\Actions\Tenancy\Tenant\UpdateSystemSettings;
use App\Actions\Tenant\Auth\User\UpdateUser;
use App\Actions\Tenant\Portfolio\Banner\DeleteBanner;
use App\Actions\Tenant\Portfolio\Banner\StoreBanner;
use App\Actions\Tenant\Portfolio\Banner\UpdateBanner;
use App\Actions\Tenant\Portfolio\Banner\UpdateBannerState;
use App\Actions\Tenant\Portfolio\Gallery\UpdateUploadedImage;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\DeletePortfolioWebsite;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\StorePortfolioWebsite;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UpdatePortfolioWebsite;
use App\Actions\UI\Tenant\Profile\UpdateProfile;

Route::post('/portfolio-website', StorePortfolioWebsite::class)->name('portfolio-website.store');
Route::patch('/portfolio-website/{portfolioWebsite}', UpdatePortfolioWebsite::class)->name('portfolio-website.update');
Route::delete('/portfolio-website/{portfolioWebsite}', DeletePortfolioWebsite::class)->name('portfolio-website.delete');
Route::post('/portfolio-website/{portfolioWebsite}/banners', [StoreBanner::class, 'inPortfolioWebsite'])->name('portfolio-website.banner.store');
Route::post('/banner', [StoreBanner::class, 'inTenant'])->name('banner.store');
Route::patch('/banner/{banner}', UpdateBanner::class)->name('banner.update');
Route::patch('/banner/{banner}/state/{state}', UpdateBannerState::class)->name('banner.update-state');
Route::delete('/banner/{banner}', DeleteBanner::class)->name('content-block.delete');

Route::patch('/images/{media}', UpdateUploadedImage::class)->name('images.update');

Route::patch('/user/{user:username}', UpdateUser::class)->name('user.update');
Route::patch('/profile', UpdateProfile::class)->name('profile.update');


Route::patch('/system-settings', UpdateSystemSettings::class)->name('system-settings.update');
Route::post('/generator', ImageGenerator::class)->name('image.generate');
