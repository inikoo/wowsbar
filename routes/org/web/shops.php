<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Market\ShopProductCategory\UI\IndexShopDepartments;
use App\Actions\Market\ShopProduct\UI\IndexShopProducts;
use App\Actions\Market\ShopProduct\UI\ShowShopProduct;
use App\Actions\Market\Shop\UI\CreateShop;
use App\Actions\Market\Shop\UI\EditShop;
use App\Actions\Market\Shop\UI\IndexShops;
use App\Actions\Market\Shop\UI\RemoveShop;
use App\Actions\Market\Shop\UI\ShowShop;
use App\Actions\Catalogue\Product\DownloadProductsTemplate;
use App\Actions\Catalogue\Product\UI\RemoveProduct;
use App\Actions\Catalogue\ProductCategory\UI\CreateDepartment;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexShops::class)->name('index');
Route::get('/create', CreateShop::class)->name('create');

Route::prefix('{shop}')->group(function () {

    Route::get('', ShowShop::class)->name('show');
    Route::get('/edit', EditShop::class)->name('edit');
    Route::get('/delete', RemoveShop::class)->name('remove');

    Route::as('show.')->group(function () {

        Route::get('/departments', [IndexShopDepartments::class, 'inShop'])->name('departments.index');
        Route::get('/products', [IndexShopProducts::class, 'inShop'])->name('products.index');
        Route::get('/products/{product}', [ShowShopProduct::class, 'inShop'])->name('products.show');
        // Route::get('/products/{product}/edit', [EditShopProduct::class, 'inShop'])->name('products.edit');
        // Route::get('/products/{product}/delete', [RemoveProduct::class, 'inShop'])->name('products.remove');

        // Route::get('/departments/create', CreateDepartment::class)->name('departments.create');
        //Route::get('/departments/create-multi', CreateDepartments::class)->name('departments.create-multi');
        //Route::get('/departments/{department}', [ShowDepartment::class, 'inShop'])->name('departments.show');
        //Route::get('/departments/{department}/edit', [EditDepartment::class, 'inShop'])->name('departments.edit');
        //Route::get('/departments/{department}/delete', [RemoveDepartment::class, 'inShop'])->name('departments.remove');

    });




});



Route::get('/uploads/template/download', DownloadProductsTemplate::class)->name('products.uploads.template.download');
