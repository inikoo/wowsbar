<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 08:44:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Catalogue\Product\UI\IndexProducts;
use App\Actions\Catalogue\Product\UI\ShowProduct;
use App\Actions\Catalogue\ProductCategory\UI\IndexDepartments;
use App\Actions\Catalogue\ProductCategory\UI\ShowDepartment;
use App\Actions\UI\Organisation\Catalogue\ShowCatalogueDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', ['icon' => 'album-collection', 'label' => 'catalogue'])->uses(ShowCatalogueDashboard::class)->name('dashboard');

Route::get('/departments', ['icon' => 'folder-tree', 'label' => 'departments'])->uses(IndexDepartments::class)->name('departments.index');
Route::prefix('departments/{productCatalogue}')->as('departments.')->group(function () {
    Route::get('/', ['icon' => 'folder-tree', 'label' => 'department'])->uses(ShowDepartment::class)->name('show');
    Route::get('/products', ['icon' => 'cube', 'label' => 'products'])->uses([IndexProducts::class,'inDepartment'])->name('show.products.index');
    Route::get('/products/{product}', ['icon' => 'cube', 'label' => 'product'])->uses([ShowProduct::class,'inDepartment'])->name('show.products.show');
});

Route::get('/products', ['icon' => 'cube', 'label' => 'products'])->uses(IndexProducts::class)->name('products.index');
Route::get('/products/{product}', ['icon' => 'cube', 'label' => 'product'])->uses(ShowProduct::class)->name('products.show');


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
