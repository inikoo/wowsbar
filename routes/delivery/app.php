<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 09:33:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Portfolio\Announcement\UI\DeliverAnnouncement;
use App\Actions\Portfolio\Announcement\UI\ShowCompiledAnnouncement;
use App\Actions\Portfolio\Banner\UI\DeliverBanner;
use App\Actions\Portfolio\Banner\UI\DeliverBannerExport;
use Illuminate\Support\Facades\Route;

Route::middleware(["delivery",])->group(function () {
    Route::get('/banners/{ulid}', DeliverBanner::class)->name('banner');
});
Route::middleware(["delivery",])->group(function () {
    Route::get('/banners-export/{ulid}', DeliverBannerExport::class)->name('banner-export');
});
Route::middleware(["delivery"])->group(function () {
    Route::get('/announcement', ShowCompiledAnnouncement::class)->name('announcement');
});
Route::middleware(["delivery"])->group(function () {
    Route::get('/announcement/{announcement:ulid}', DeliverAnnouncement::class)->name('announcement.preview');
});
