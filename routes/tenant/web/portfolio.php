<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 13 Oct 2022 15:46:44 Central European Summer Time, Plane Malaga - East Midlands UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\Gallery\UI\ShowGallery;
use App\Actions\Gallery\UI\UploadedImages\ShowUploadedImage;
use App\Actions\Gallery\UploadImagesToGallery;
use App\Actions\Portfolio\ContentBlock\Banners\UI\CreateBanner;
use App\Actions\Portfolio\ContentBlock\Banners\UI\EditBanner;
use App\Actions\Portfolio\ContentBlock\Banners\UI\IndexBanners;
use App\Actions\Portfolio\ContentBlock\Banners\UI\RemoveBanner;
use App\Actions\Portfolio\ContentBlock\Banners\UI\ShowBanner;
use App\Actions\Portfolio\ContentBlock\Banners\UI\ShowBannerWorkshop;
use App\Actions\Portfolio\ContentBlock\Banners\UI\ShowDeletedBanner;
use App\Actions\Portfolio\ContentBlock\UploadImagesToContentBlock;
use App\Actions\Portfolio\Website\UI\CreateWebsite;
use App\Actions\Portfolio\Website\UI\EditWebsite;
use App\Actions\Portfolio\Website\UI\IndexWebsites;
use App\Actions\Portfolio\Website\UI\RemoveWebsite;
use App\Actions\Portfolio\Website\UI\ShowWebsite;
use App\Actions\UI\Websites\PortfolioDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', PortfolioDashboard::class)->name('dashboard');

Route::get('/websites', IndexWebsites::class)->name('websites.index');
Route::get('/websites/create', CreateWebsite::class)->name('websites.create');
Route::get('/websites/{website}', ShowWebsite::class)->name('websites.show');
Route::get('/websites/{website}/edit', EditWebsite::class)->name('websites.edit');
Route::get('/websites/{website}/delete', RemoveWebsite::class)->withTrashed()->name('websites.remove');
Route::get('/websites/{website}/banners/create', [CreateBanner::class,'inWebsite'])->name('websites.show.banners.create');
Route::get('/websites/{website}/banners', [IndexBanners::class,'inWebsite'])->name('websites.show.banners.index');
Route::get('/websites/{website}/banners/{banner}', [ShowBanner::class,'inWebsite'])->name('websites.show.banners.show');
Route::get('/websites/{website}/banners/{banner}/edit', [EditBanner::class,'inWebsite'])->name('websites.show.banners.edit');
Route::get('/websites/{website}/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inWebsite'])->name('websites.show.banners.workshop');
Route::post('/websites/{website}/banners/{banner}/workshop/images', [UploadImagesToContentBlock::class, 'inBannerInWebsite'])->name('websites.show.banners.workshop.images.store');
Route::get('/websites/{website}/banners/{banner}/delete', [RemoveBanner::class,'inWebsite'])->withTrashed()->name('websites.show.banners.remove');
Route::get('/banners', [IndexBanners::class,'inTenant'])->name('banners.index');
Route::get('/banners/create', [CreateBanner::class,'inTenant'])->name('banners.create');

Route::get('/banners/{banner}', [ShowBanner::class,'inTenant'])->name('banners.show');
Route::get('/banners/{banner}/edit', [EditBanner::class,'inTenant'])->name('banners.edit');
Route::get('/banners/{banner}/workshop', [ShowBannerWorkshop::class,'inTenant'])->name('banners.workshop');
Route::post('/banners/{banner}/workshop/images', [UploadImagesToContentBlock::class, 'inBanner'])->name('banners.workshop.images.store');

Route::get('/banners/{banner}/delete', [RemoveBanner::class,'inTenant'])->name('banners.remove');
Route::get('/banners/{banner}/deleted', [ShowDeletedBanner::class,'inTenant'])->withTrashed()->name('banners.deleted');


//Route::get('/banners/{banner}/delete', [RemoveBanner::class,'inTenant'])->name('banners.remove');

Route::prefix('gallery')->group(function () {
    Route::get('/', ShowGallery::class)->name('gallery');
    Route::get('/images/{media}', ShowUploadedImage::class)->name('images.show');
    Route::post('/images', UploadImagesToGallery::class)->name('images.upload');
});

//Route::get('/images', IndexImages::class)->name('images.index');
