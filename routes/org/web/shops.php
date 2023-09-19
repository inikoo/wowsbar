<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Market\Product\UI\CreateProduct;
use App\Actions\Organisation\Market\Product\UI\IndexProducts;
use App\Actions\Organisation\Market\ProductCategory\UI\CreateDepartment;
use App\Actions\Organisation\Market\ProductCategory\UI\IndexDepartments;
use App\Actions\Organisation\Market\Shop\UI\CreateShop;
use App\Actions\Organisation\Market\Shop\UI\EditShop;
use App\Actions\Organisation\Market\Shop\UI\IndexShops;
use App\Actions\Organisation\Market\Shop\UI\RemoveShop;
use App\Actions\Organisation\Market\Shop\UI\ShowShop;
use App\Actions\Organisation\Web\Website\UI\CreateWebsite;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexShops::class)->name('index');
Route::get('/create', CreateShop::class)->name('create');
Route::get('/{shop}', ShowShop::class)->name('show');
Route::get('/{shop}/website/create', CreateWebsite::class)->name('show.website.create');
Route::get('/{shop}/edit', EditShop::class)->name('edit');
Route::get('/{shop}/delete', RemoveShop::class)->name('remove');

Route::get('/{shop}/departments/create', CreateDepartment::class)->name('show.departments.create');
//Route::get('/{shop}/departments/create-multi', CreateDepartments::class)->name('show.departments.create-multi');
Route::get('/{shop}/departments', [IndexDepartments::class, 'inShop'])->name('show.departments.index');
//Route::get('/{shop}/departments/{department}', [ShowDepartment::class, 'inShop'])->name('show.departments.show');
//Route::get('/{shop}/departments/{department}/edit', [EditDepartment::class, 'inShop'])->name('show.departments.edit');
//Route::get('/{shop}/departments/{department}/delete', [RemoveDepartment::class, 'inShop'])->name('show.departments.remove');


Route::get('/{shop}/products', [IndexProducts::class, 'inShop'])->name('show.products.index');
Route::get('/shops/{shop}/products/create', CreateProduct::class)->name('show.products.create');
Route::get('/{shop}/products/{product}', [ShowProduct::class, 'inShop'])->name('show.products.show');
Route::get('/{shop}/products/{product}/edit', [EditProduct::class, 'inShop'])->name('show.products.edit');
Route::get('/{shop}/products/{product}/delete', [RemoveProduct::class, 'inShop'])->name('show.products.remove');
