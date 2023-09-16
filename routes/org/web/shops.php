<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Market\Shop\UI\CreateShop;
use App\Actions\Organisation\Market\Shop\UI\IndexShops;
use App\Actions\Organisation\Market\Shop\UI\ShowShop;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexShops::class)->name('index');
Route::get('/create', CreateShop::class)->name('create');
Route::get('/{shop}', ShowShop::class)->name('show');
