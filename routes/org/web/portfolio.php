<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Portfolio\Banner\UI\CreateBanner;
use App\Actions\Portfolio\Banner\UI\DuplicateBanner;
use App\Actions\Portfolio\Banner\UI\EditBanner;
use App\Actions\Portfolio\Banner\UI\IndexBanners;
use App\Actions\Portfolio\Banner\UI\RemoveBanner;
use App\Actions\Portfolio\Banner\UI\ShowBanner;
use App\Actions\Portfolio\Banner\UI\ShowBannerWorkshop;
use App\Actions\Portfolio\Banner\UI\ShowDeletedBanner;
use App\Actions\Portfolio\Banner\UploadImagesToBanner;
use App\Actions\Portfolio\Gallery\DeleteUploadedImage;
use App\Actions\Portfolio\Gallery\UI\IndexStockImages;
use App\Actions\Portfolio\Gallery\UI\ShowGallery;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\EditUploadedImage;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\IndexUploadedImages;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\ShowUploadedImage;
use App\Actions\Portfolio\Gallery\UploadImagesToGallery;
use App\Actions\Portfolio\PortfolioWebsite\UI\CreatePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UI\EditPortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UI\IndexPortfolioWebsites;
use App\Actions\Portfolio\PortfolioWebsite\UI\RemovePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\Portfolio\Snapshot\UI\IndexSnapshots;
use App\Actions\Portfolio\Snapshot\UI\ShowSnapshot;
use App\Actions\Portfolio\Uploads\DownloadPortfolioWebsiteUploadsTemplate;
use App\Actions\Portfolio\Uploads\IndexPortfolioWebsiteUploads;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use Illuminate\Support\Facades\Route;

Route::get('/', [
    'uses'  => IndexPortfolioWebsites::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('index');

Route::get('/create', CreatePortfolioWebsite::class)->name('create');
Route::get('/{portfolioWebsite}', [
        'uses'  => ShowPortfolioWebsite::class,
        'icon'  => 'globe',
        'label' => 'websites'
    ])->name('show');

Route::get('/{portfolioWebsite}/edit', EditPortfolioWebsite::class)->name('edit');
Route::get('/{portfolioWebsite}/delete', RemovePortfolioWebsite::class)->withTrashed()->name('remove');

Route::get('/{portfolioWebsite}/banners/create', [CreateBanner::class, 'inPortfolioWebsite'])->name('show.banners.create');
Route::get('/{portfolioWebsite}/banners', [IndexBanners::class, 'inPortfolioWebsite'])->name('show.banners.index');


Route::get('/{portfolioWebsite}/banners/{banner}', [
    'uses'  => ShowBanner::class . '@inPortfolioWebsite',
    'icon'  => 'globe',
    'label' => 'banner'
])->name('show.banners.show');

Route::get('/{portfolioWebsite}/banners/{banner}/edit', [
    'uses'  => EditBanner::class,
    'icon'  => 'globe',
    'label' => 'banner'
])->name('show.banners.edit');

Route::get('/{portfolioWebsite}/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inPortfolioWebsite'])->name('show.banners.workshop');
Route::post('/{portfolioWebsite}/banners/{banner}/workshop/images', [UploadImagesToBanner::class, 'inBannerInPortfolioWebsite'])->name('show.banners.workshop.images.store');
Route::get('/{portfolioWebsite}/banners/{banner}/delete', [RemoveBanner::class, 'inPortfolioWebsite'])->withTrashed()->name('show.banners.remove');
Route::get('/banners', [IndexBanners::class, 'inCustomer'])->name('banners.index');

Route::get('/{portfolioWebsite}/banners/{banner}/snapshots', [IndexSnapshots::class, 'inWebsite'])->name('snapshots.index');
Route::get('/{portfolioWebsite}/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inWebsite'])->name('snapshots.show');
