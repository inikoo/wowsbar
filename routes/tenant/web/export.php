<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 09 Sept 2022 18:32:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\Tenant\Portfolio\Banner\ExportBanners;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\ExportWebsites;
use App\Actions\Tenant\Portfolio\Snapshot\ExportSnapshots;

Route::get('/banners', ExportBanners::class)->name('banners.index');
Route::get('/websites', ExportWebsites::class)->name('websites.index');
Route::get('/snapshots', ExportSnapshots::class)->name('snapshots.index');
Route::get('/histories', ExportSnapshots::class)->name('histories.index');
