<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Helpers\Snapshot\UI\IndexSnapshots;
use App\Actions\Helpers\Snapshot\UI\ShowSnapshot;
use App\Actions\Portfolio\Banner\UI\CreateBanner;
use App\Actions\Portfolio\Banner\UI\DuplicateBanner;
use App\Actions\Portfolio\Banner\UI\EditBanner;
use App\Actions\Portfolio\Banner\UI\IndexBanners;
use App\Actions\Portfolio\Banner\UI\RemoveBanner;
use App\Actions\Portfolio\Banner\UI\ShowBanner;
use App\Actions\Portfolio\Banner\UI\ShowBannerWorkshop;
use App\Actions\Portfolio\Banner\UI\ShowDeletedBanner;
use App\Actions\Portfolio\Banner\UploadImagesToBanner;
use App\Actions\Portfolio\PortfolioSocialAccount\UI\CreatePortfolioSocialAccount;
use App\Actions\Portfolio\PortfolioSocialAccount\UI\EditPortfolioSocialAccount;
use App\Actions\Portfolio\PortfolioSocialAccount\UI\IndexPortfolioSocialAccounts;
use App\Actions\Portfolio\PortfolioSocialAccount\UI\ShowPortfolioSocialAccount;
use App\Actions\Portfolio\PortfolioWebsite\UI\CreatePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UI\EditPortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UI\IndexPortfolioWebsites;
use App\Actions\Portfolio\PortfolioWebsite\UI\RemovePortfolioWebsite;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\Portfolio\Uploads\DownloadPortfolioWebsiteUploadsTemplate;
use App\Actions\Portfolio\Uploads\IndexPortfolioWebsiteUploads;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', ['uses' => ShowPortfolio::class, 'icon' => 'briefcase', 'label' => 'portfolio'])->name('dashboard');


Route::prefix('websites')->name('websites.')->group(function () {
    Route::get('', ['icon' => 'globe', 'label' => 'websites'])->uses(IndexPortfolioWebsites::class)->name('index');
    Route::get('create', CreatePortfolioWebsite::class)->name('create');

    Route::prefix('{portfolioWebsite}')->group(function () {
        Route::get('', ['icon' => 'globe', 'label' => 'websites'])->uses(ShowPortfolioWebsite::class)->name('show');
        Route::get('/edit', EditPortfolioWebsite::class)->name('edit');
        Route::get('/delete', RemovePortfolioWebsite::class)->withTrashed()->name('remove');

        Route::get('/banners/create', [CreateBanner::class, 'inPortfolioWebsite'])->name('show.banners.create');
        Route::get('/banners', [IndexBanners::class, 'inPortfolioWebsite'])->name('show.banners.index');
        Route::get('/banners/{banner}', ['icon' => 'globe', 'label' => 'banner'])->uses([ShowBanner::class, 'inPortfolioWebsite'])->name('show.banners.show');
        Route::get('/banners/{banner}/edit', ['icon' => 'globe', 'label' => 'banner'])->uses(EditBanner::class)->name('show.banners.edit');
        Route::get('/banners/{banner}/workshop', [ShowBannerWorkshop::class, 'inPortfolioWebsite'])->name('show.banners.workshop');
        Route::post('/banners/{banner}/workshop/images', [UploadImagesToBanner::class, 'inBannerInPortfolioWebsite'])->name('show.banners.workshop.images.store');
        Route::get('/banners/{banner}/delete', [RemoveBanner::class, 'inPortfolioWebsite'])->withTrashed()->name('show.banners.remove');
        Route::get('/banners/{banner}/snapshots', [IndexSnapshots::class, 'inWebsite'])->name('websites.snapshots.index');
        Route::get('/banners/{banner}/snapshots/{snapshot}', [ShowSnapshot::class, 'inWebsite'])->name('websites.snapshots.show');
    });
});


Route::get('/portfolio-websites/uploads/history', IndexPortfolioWebsiteUploads::class)->name('website.uploads.history');
Route::get('/portfolio-websites/uploads/template/download', DownloadPortfolioWebsiteUploadsTemplate::class)->name('website.uploads.template.download');
Route::get('/social-accounts', IndexPortfolioSocialAccounts::class)->name('social-accounts.index');
Route::get('/social-accounts/create', CreatePortfolioSocialAccount::class)->name('social-accounts.create');
Route::get('/social-accounts/{portfolioSocialAccount}', ShowPortfolioSocialAccount::class)->name('social-accounts.show');
Route::get('/social-accounts/{portfolioSocialAccount}/edit', EditPortfolioSocialAccount::class)->name('social-accounts.edit');
