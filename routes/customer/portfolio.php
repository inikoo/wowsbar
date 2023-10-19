<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


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
    });
});


Route::get('/portfolio-websites/uploads/history', IndexPortfolioWebsiteUploads::class)->name('website.uploads.history');
Route::get('/portfolio-websites/uploads/template/download', DownloadPortfolioWebsiteUploadsTemplate::class)->name('website.uploads.template.download');


Route::get('/social-accounts', IndexPortfolioSocialAccounts::class)->name('social-accounts.index');
Route::get('/social-accounts/create', CreatePortfolioSocialAccount::class)->name('social-accounts.create');
Route::get('/social-accounts/{portfolioSocialAccount}', ShowPortfolioSocialAccount::class)->name('social-accounts.show');
Route::get('/social-accounts/{portfolioSocialAccount}/edit', EditPortfolioSocialAccount::class)->name('social-accounts.edit');
