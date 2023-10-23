<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:11:26 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Subscriptions\CustomerWebsite\UI\IndexGoogleAdsCustomerWebsites;
use App\Actions\UI\Organisation\Catalogue\ShowGoogleAdsDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/ppc/dashboard');});
Route::get('/dashboard', ['icon'  => 'globe', 'label' => 'ppc'])->uses(ShowGoogleAdsDashboard::class)->name('dashboard');

Route::get('/websites', [
    'uses'  => IndexGoogleAdsCustomerWebsites::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('websites.index');
