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
/*
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






Route::get('websites/{portfolioWebsite}/banners/{banner}/snapshots', [IndexSnapshots::class, 'inWebsite'])->name('websites.snapshots.index');
Route::get('websites/{portfolioWebsite}/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inWebsite'])->name('websites.snapshots.show');


Route::get('/portfolio-websites/uploads/history', IndexPortfolioWebsiteUploads::class)->name('website.uploads.history');
Route::get('/portfolio-websites/uploads/template/download', DownloadPortfolioWebsiteUploadsTemplate::class)->name('website.uploads.template.download');
*/




Route::prefix('gallery')->group(function () {
    Route::get('/', ShowGallery::class)->name('gallery');
    Route::get('/images/{media}', ShowUploadedImage::class)->name('images.show');
    Route::get('/images/{media}/edit', EditUploadedImage::class)->name('images.edit');
    Route::get('/images/{media}/delete', DeleteUploadedImage::class)->name('images.remove');
    Route::post('/images', UploadImagesToGallery::class)->name('images.upload');
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
});
