<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Web\Webpage\IndexWebpages;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowWebsite::class)->name('show');
Route::get('/webpages', IndexWebpages::class)->name('webpages.index');
Route::get('/webpages/{webpage}', IndexWebpages::class)->name('webpages.show');
