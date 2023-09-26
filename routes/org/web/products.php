<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 08:44:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Organisation\Catalogue\ShowCatalogueDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [
    'uses'  => ShowCatalogueDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('dashboard');
Route::get('/seo', [
    'uses'  => ShowCatalogueDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('seo.dashboard');

Route::get('/social', [
    'uses'  => ShowCatalogueDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('social.dashboard');


Route::get('/google-ads', [
    'uses'  => ShowCatalogueDashboard::class,
    'icon'  => 'globe',
    'label' => 'websites'
])->name('google-ads.dashboard');
