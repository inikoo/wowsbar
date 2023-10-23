<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:11:46 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\CaaS\Banner\UI\ShowBanner;
use App\Actions\Portfolios\CustomerWebsite\UI\IndexCaasCustomerWebsites;
use App\Actions\UI\Organisation\Catalogue\ShowCaaSDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/caas/dashboard');})->name('root');
Route::get('/dashboard', ['icon'  => 'globe', 'label' => 'Caas'])->uses(ShowCaaSDashboard::class)->name('dashboard');

Route::get('{banner}', ['icon'  => 'globe', 'label' => 'Caas'])->uses([ShowBanner::class, 'inPortfolioWebsite'])->name('banner.show');

Route::get('/websites', [
    'uses'  => IndexCaasCustomerWebsites::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('websites.index');
