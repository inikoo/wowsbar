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

Route::get('/dashboard', ['uses'  => ShowPortfolio::class, 'icon'  => 'briefcase', 'label' => 'portfolio'])->name('dashboard');

Route::get('/websites', ['uses'  => IndexPortfolioWebsites::class,'icon'  => 'globe', 'label' => 'websites'])->name('websites.index');
Route::get('/websites/create', CreatePortfolioWebsite::class)->name('websites.create');
Route::get('/websites/{portfolioWebsite}', ['uses'  => ShowPortfolioWebsite::class, 'icon'  => 'globe', 'label' => 'websites'])->name('websites.show');
Route::get('/websites/{portfolioWebsite}/edit', EditPortfolioWebsite::class)->name('websites.edit');
Route::get('/websites/{portfolioWebsite}/delete', RemovePortfolioWebsite::class)->withTrashed()->name('websites.remove');

Route::get('/websites/{portfolioWebsite}/banners/create', [CreateBanner::class, 'inPortfolioWebsite'])->name('websites.show.banners.create');
Route::get('/websites/{portfolioWebsite}/banners', [IndexBanners::class, 'inPortfolioWebsite'])->name('websites.show.banners.index');


Route::get('/websites/{portfolioWebsite}/banners/{banner}', ['icon'  => 'globe', 'label' => 'banner'])->uses([ShowBanner::class,'inPortfolioWebsite'])->name('websites.show.banners.show');

Route::get('/websites/{portfolioWebsite}/banners/{banner}/edit', [
    'uses'  => EditBanner::class,
    'icon'  => 'globe',
    'label' => 'banner'
])->name('websites.show.banners.edit');

Route::get('/websites/{portfolioWebsite}/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inPortfolioWebsite'])->name('websites.show.banners.workshop');
Route::post('/websites/{portfolioWebsite}/banners/{banner}/workshop/images', [UploadImagesToBanner::class, 'inBannerInPortfolioWebsite'])->name('websites.show.banners.workshop.images.store');
Route::get('/websites/{portfolioWebsite}/banners/{banner}/delete', [RemoveBanner::class, 'inPortfolioWebsite'])->withTrashed()->name('websites.show.banners.remove');
Route::get('/banners', [IndexBanners::class, 'inCustomer'])->name('banners.index');

Route::get('websites/{portfolioWebsite}/banners/{banner}/snapshots', [IndexSnapshots::class, 'inWebsite'])->name('websites.snapshots.index');
Route::get('websites/{portfolioWebsite}/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inWebsite'])->name('websites.snapshots.show');

Route::get('/banners/create', [CreateBanner::class, 'inCustomer'])->name('banners.create');

Route::get('/banners/{banner}', [ShowBanner::class, 'inCustomer'])->name('banners.show');
Route::get('/banners/{banner}/edit', EditBanner::class)->name('banners.edit');
Route::get('/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inCustomer'])->name('banners.workshop');
Route::post('/banners/{banner}/workshop/images', [UploadImagesToBanner::class, 'inBanner'])->name('banners.workshop.images.store');

Route::get('/banners/{banner}/delete', [RemoveBanner::class, 'inCustomer'])->name('banners.remove');
Route::get('/banners/{banner}/deleted', [ShowDeletedBanner::class, 'inCustomer'])->withTrashed()->name('banners.deleted');

Route::get('/banners/{banner}/duplicate', DuplicateBanner::class)->name('banners.duplicate');

Route::get('/banners/{banner}/snapshots', [IndexSnapshots::class, 'inBanner'])->name('banners.snapshots.index');
Route::get('/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inBanner'])->name('banners.snapshots.show');

//Route::get('/banners/{banner}/delete', [RemoveBanner::class,'inCustomer'])->name('banners.remove');



//Route::get('/images', IndexImages::class)->name('images.index');


Route::get('/portfolio-websites/uploads/history', IndexPortfolioWebsiteUploads::class)->name('website.uploads.history');
Route::get('/portfolio-websites/uploads/template/download', DownloadPortfolioWebsiteUploadsTemplate::class)->name('website.uploads.template.download');
