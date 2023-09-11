<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Organisation\Web\Webpage\IndexWebpages;
use App\Actions\Organisation\Web\Website\UI\EditWebsite;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use App\Actions\Organisation\Web\Website\UI\ShowWebsiteWorkshop;
use App\Actions\Organisation\Web\Website\UI\ShowWebsiteWorkshopPreview;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowWebsite::class)->name('show');
Route::get('/edit', EditWebsite::class)->name('edit');
Route::get('/workshop', ShowWebsiteWorkshop::class)->name('workshop');
Route::get('/workshop/preview', ShowWebsiteWorkshopPreview::class)->name('preview');
Route::get('/webpages', IndexWebpages::class)->name('webpages.index');
Route::get('/webpages/{webpage}', IndexWebpages::class)->name('webpages.show');
