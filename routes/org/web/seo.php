<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:09:30 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Subscriptions\CustomerWebsite\UI\IndexSeoCustomerWebsites;
use App\Actions\UI\Organisation\Catalogue\ShowSeoDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/seo/dashboard');})->name('root');
Route::get('/dashboard', ['icon'  => 'globe', 'label' => 'seo'])->uses(ShowSeoDashboard::class)->name('dashboard');

Route::get('/websites', [
    'uses'  => IndexSeoCustomerWebsites::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('websites.index');
