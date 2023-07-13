<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Oct 2022 15:46:44 Central European Summer Time, Plane Malaga - East Midlands UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\UI\Websites\PortfolioDashboard;
use App\Actions\Portfolio\Website\UI\CreateWebsite;
use App\Actions\Portfolio\Website\UI\EditWebsite;
use App\Actions\Portfolio\Website\UI\IndexWebsites;
use App\Actions\Portfolio\Website\UI\RemoveWebsite;
use App\Actions\Portfolio\Website\UI\ShowWebsite;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', PortfolioDashboard::class)->name('dashboard');

Route::get('/websites', IndexWebsites::class)->name('websites.index');
Route::get('/websites/create', CreateWebsite::class)->name('websites.create');
Route::get('/{website}', ShowWebsite::class)->name('websites.show');
Route::get('/{website}/edit', EditWebsite::class)->name('websites.edit');
Route::get('/{website}/delete', RemoveWebsite::class)->name('websites.remove');
//Route::get('/{website}/banners/create', [CreateBanner::class,'inWebsite'])->name('websites.show.banners.create');
//Route::get('/{website}/banners', [IndexBanners::class,'inWebsite'])->name('websites.show.banners.index');
//Route::get('/{website}/{banner}', [ShowBanner::class,'inWebsite'])->name('websites.show.banners.show');
//Route::get('/{website}/{banner}/edit', [EditBanner::class,'inWebsite'])->name('websites.show.banners.edit');
//Route::get('/{website}/{banner}/delete', [RemoveBanner::class,'inWebsite'])->name('websites.show.banners.remove');

//Route::get('/banners', [IndexBanners::class,'inTenant'])->name('banners.index');
//Route::get('/{banner}', [ShowBanner::class,'inTenant'])->name('banners.show');
//Route::get('/{banner}/edit', [EditBanner::class,'inTenant'])->name('banners.edit');
//Route::get('/{banner}/delete', [RemoveBanner::class,'inTenant'])->name('banners.remove');
