<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Portfolio\Banner\UI\CreateBanner;
use App\Actions\Portfolio\Banner\UI\EditBanner;
use App\Actions\Portfolio\Banner\UI\IndexBanners;
use App\Actions\Portfolio\Banner\UI\RemoveBanner;
use App\Actions\Portfolio\Banner\UI\ShowBanner;
use App\Actions\Portfolio\Banner\UI\ShowBannerWorkshop;
use App\Actions\Portfolio\Gallery\DeleteUploadedImage;
use App\Actions\Portfolio\Gallery\UI\IndexStockImages;
use App\Actions\Portfolio\Gallery\UI\ShowGallery;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\EditUploadedImage;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\IndexUploadedImages;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\ShowUploadedImage;
use App\Actions\Portfolio\PortfolioWebsite\UI\IndexBannerPortfolioWebsites;
use App\Actions\Portfolio\Snapshot\UI\IndexSnapshots;
use App\Actions\Portfolio\Snapshot\UI\ShowSnapshot;
use App\Actions\UI\Customer\Banners\ShowBannersDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', ['icon'  => 'rectangle-wide', 'label' => 'banners dashboard'])->uses(ShowBannersDashboard::class)->name('dashboard');
Route::get('/websites', ['icon' => 'globe', 'label' => 'websites'])->uses(IndexBannerPortfolioWebsites::class)->name('websites.index');


Route::prefix('gallery')->group(function () {
    Route::get('/', ShowGallery::class)->name('gallery');
    Route::get('/images/{media}', ShowUploadedImage::class)->name('images.show');
    Route::get('/images/{media}/edit', EditUploadedImage::class)->name('images.edit');
    Route::get('/images/{media}/delete', DeleteUploadedImage::class)->name('images.remove');
    Route::get('/uploaded/images', IndexUploadedImages::class)->name('uploaded.images');
    Route::get('/stock/images', IndexStockImages::class)->name('stock.images');
});

Route::get('', [IndexBanners::class, 'inCustomer'])->name('index');
Route::get('/create', [CreateBanner::class, 'inCustomer'])->name('create');
Route::prefix('{banner}')->group(function () {
    Route::get('', ['icon' => 'globe', 'label' => 'banner'])->uses(ShowBanner::class)->name('show');
    Route::get('edit', ['icon' => 'globe', 'label' => 'banner'])->uses(EditBanner::class)->name('edit');
    Route::get('workshop', ['icon' => 'globe', 'label' => 'banner'])->uses(ShowBannerWorkshop::class)->name('workshop');
    Route::get('delete', ['icon' => 'globe', 'label' => 'banner'])->uses(RemoveBanner::class)->name('remove');
    Route::get('snapshots', [IndexSnapshots::class, 'inBanner'])->name('show.snapshots.index');
    Route::get('snapshots/{snapshot}', [ShowSnapshot::class, 'inBanner'])->name('show.snapshots.show');
});
