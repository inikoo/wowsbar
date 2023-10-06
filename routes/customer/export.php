<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 09 Sept 2022 18:32:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\Auth\User\ExportUsers;
use App\Actions\Helpers\History\ExportHistories;
use App\Actions\Helpers\Snapshot\ExportSnapshots;
use App\Actions\Media\ExportStockImages;
use App\Actions\Portfolio\Banner\ExportBanners;
use App\Actions\Portfolio\Gallery\ExportUploadedImages;
use App\Actions\Portfolio\PortfolioWebsite\ExportWebsites;

Route::get('/users', ExportUsers::class)->name('users.index');
Route::get('/banners', ExportBanners::class)->name('banners.index');
Route::get('/websites', ExportWebsites::class)->name('websites.index');
Route::get('/snapshots', ExportSnapshots::class)->name('snapshots.index');
Route::get('/histories', ExportHistories::class)->name('histories.index');
Route::get('/images/uploaded', ExportUploadedImages::class)->name('uploaded.images.index');
Route::get('/images/stock', ExportStockImages::class)->name('stock.images.index');
