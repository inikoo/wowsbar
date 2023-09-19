<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Auth\User\ExportUsers;
use App\Actions\Helpers\History\ExportHistories;
use App\Actions\Media\ExportStockImages;
use App\Actions\Portfolio\Banner\ExportBanners;
use App\Actions\Portfolio\Gallery\ExportUploadedImages;
use App\Actions\Portfolio\PortfolioWebsite\ExportWebsites;
use App\Actions\Portfolio\Snapshot\ExportSnapshots;

Route::get('/users', ExportUsers::class)->name('users.index');
Route::get('/banners', ExportBanners::class)->name('banners.index');
Route::get('/websites', ExportWebsites::class)->name('websites.index');
Route::get('/snapshots', ExportSnapshots::class)->name('snapshots.index');
Route::get('/histories', ExportHistories::class)->name('histories.index');
Route::get('/images/uploaded', ExportUploadedImages::class)->name('uploaded.images.index');
Route::get('/images/stock', ExportStockImages::class)->name('stock.images.index');
