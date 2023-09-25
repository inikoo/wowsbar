<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Portfolios\CustomerWebsite\UI\IndexCustomerWebsites;
use App\Actions\UI\Customer\GoogleAds\ShowGoogleAdsDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [
    'uses'  => ShowGoogleAdsDashboard::class,
    'icon'  => 'briefcase',
    'label' => 'portfolio'
])->name('dashboard');

Route::get('/websites', [
    'uses'  => IndexCustomerWebsites::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('websites.index');
