<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Oct 2022 15:46:44 Central European Summer Time, Plane Malaga - East Midlands UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\Tenant\Portfolio\Banner\UI\CreateBanner;
use App\Actions\Tenant\Portfolio\Banner\UI\DuplicateBanner;
use App\Actions\Tenant\Portfolio\Banner\UI\EditBanner;
use App\Actions\Tenant\Portfolio\Banner\UI\IndexBanners;
use App\Actions\Tenant\Portfolio\Banner\UI\RemoveBanner;
use App\Actions\Tenant\Portfolio\Banner\UI\ShowBanner;
use App\Actions\Tenant\Portfolio\Banner\UI\ShowBannerWorkshop;
use App\Actions\Tenant\Portfolio\Banner\UI\ShowDeletedBanner;
use App\Actions\Tenant\Portfolio\Banner\UploadImagesToBanner;
use App\Actions\Tenant\Portfolio\Gallery\DeleteUploadedImage;
use App\Actions\Tenant\Portfolio\Gallery\UI\IndexStockImages;
use App\Actions\Tenant\Portfolio\Gallery\UI\ShowGallery;
use App\Actions\Tenant\Portfolio\Gallery\UI\UploadedImages\EditUploadedImage;
use App\Actions\Tenant\Portfolio\Gallery\UI\UploadedImages\IndexUploadedImages;
use App\Actions\Tenant\Portfolio\Gallery\UI\UploadedImages\ShowUploadedImage;
use App\Actions\Tenant\Portfolio\Gallery\UploadImagesToGallery;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UI\CreatePortfolioWebsite;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UI\EditPortfolioWebsite;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UI\IndexPortfolioWebsites;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UI\RemovePortfolioWebsite;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\Tenant\Portfolio\Snapshot\UI\IndexSnapshots;
use App\Actions\Tenant\Portfolio\Snapshot\UI\ShowSnapshot;
use App\Actions\Tenant\Portfolio\Uploads\IndexPortfolioWebsiteUploads;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolio;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [
    'uses'  => ShowPortfolio::class,
    'icon'  => 'briefcase',
    'label' => 'portfolio'
])->name('dashboard');

Route::get('/websites', [
    'uses'  => IndexPortfolioWebsites::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('websites.index');
Route::get('/websites/create', CreatePortfolioWebsite::class)->name('websites.create');
Route::get('/websites/{portfolioWebsite}', [
        'uses'  => ShowPortfolioWebsite::class,
        'icon'  => 'globe',
        'label' => 'websites'
    ])->name('websites.show');
Route::get('/websites/{portfolioWebsite}/edit', EditPortfolioWebsite::class)->name('websites.edit');
Route::get('/websites/{portfolioWebsite}/delete', RemovePortfolioWebsite::class)->withTrashed()->name('websites.remove');

Route::get('/websites/{portfolioWebsite}/banners/create', [CreateBanner::class, 'inPortfolioWebsite'])->name('websites.show.banners.create');
Route::get('/websites/{portfolioWebsite}/banners', [IndexBanners::class, 'inPortfolioWebsite'])->name('websites.show.banners.index');


Route::get('/websites/{portfolioWebsite}/banners/{banner}', [
    'uses'  => ShowBanner::class . '@inPortfolioWebsite',
    'icon'  => 'globe',
    'label' => 'banner'
])->name('websites.show.banners.show');

Route::get('/websites/{portfolioWebsite}/banners/{banner}/edit', [
    'uses'  => EditBanner::class,
    'icon'  => 'globe',
    'label' => 'banner'
])->name('websites.show.banners.edit');

Route::get('/websites/{portfolioWebsite}/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inPortfolioWebsite'])->name('websites.show.banners.workshop');
Route::post('/websites/{portfolioWebsite}/banners/{banner}/workshop/images', [UploadImagesToBanner::class, 'inBannerInPortfolioWebsite'])->name('websites.show.banners.workshop.images.store');
Route::get('/websites/{portfolioWebsite}/banners/{banner}/delete', [RemoveBanner::class, 'inPortfolioWebsite'])->withTrashed()->name('websites.show.banners.remove');
Route::get('/banners', [IndexBanners::class, 'inTenant'])->name('banners.index');

Route::get('websites/{portfolioWebsite}/banners/{banner}/snapshots', [IndexSnapshots::class, 'inWebsite'])->name('websites.snapshots.index');
Route::get('websites/{portfolioWebsite}/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inWebsite'])->name('websites.snapshots.show');

Route::get('/banners/create', [CreateBanner::class, 'inTenant'])->name('banners.create');

Route::get('/banners/{banner}', [ShowBanner::class, 'inTenant'])->name('banners.show');
Route::get('/banners/{banner}/edit', EditBanner::class)->name('banners.edit');
Route::get('/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inTenant'])->name('banners.workshop');
Route::post('/banners/{banner}/workshop/images', [UploadImagesToBanner::class, 'inBanner'])->name('banners.workshop.images.store');

Route::get('/banners/{banner}/delete', [RemoveBanner::class, 'inTenant'])->name('banners.remove');
Route::get('/banners/{banner}/deleted', [ShowDeletedBanner::class, 'inTenant'])->withTrashed()->name('banners.deleted');

Route::get('/banners/{banner}/duplicate', DuplicateBanner::class)->name('banners.duplicate');

Route::get('/banners/{banner}/snapshots', [IndexSnapshots::class, 'inBanner'])->name('banners.snapshots.index');
Route::get('/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inBanner'])->name('banners.snapshots.show');

//Route::get('/banners/{banner}/delete', [RemoveBanner::class,'inTenant'])->name('banners.remove');

Route::prefix('gallery')->group(function () {
    Route::get('/', ShowGallery::class)->name('gallery');
    Route::get('/images/{media}', ShowUploadedImage::class)->name('images.show');
    Route::get('/images/{media}/edit', EditUploadedImage::class)->name('images.edit');
    Route::get('/images/{media}/delete', DeleteUploadedImage::class)->name('images.remove');
    Route::post('/images', UploadImagesToGallery::class)->name('images.upload');


    Route::get('/uploaded/images', IndexUploadedImages::class)->name('uploaded.images');
    Route::get('/stock/images', IndexStockImages::class)->name('stock.images');
});

//Route::get('/images', IndexImages::class)->name('images.index');


Route::get('/portfolio-websites/uploads/history', IndexPortfolioWebsiteUploads::class)->name('website.uploads.history');
