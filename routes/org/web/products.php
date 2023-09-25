<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 08:44:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Organisation\ProductManagement\ShowProductManagementDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [
    'uses'  => ShowProductManagementDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('dashboard');
Route::get('/seo', [
    'uses'  => ShowProductManagementDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('seo.dashboard');

Route::get('/social', [
    'uses'  => ShowProductManagementDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('social.dashboard');


Route::get('/google-ads', [
    'uses'  => ShowProductManagementDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('google-ads.dashboard');
