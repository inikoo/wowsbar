<?php

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 20 Jun 2024 12:54:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

use App\Actions\Portfolio\Gallery\UI\StockImages\IndexStockImages;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\IndexUploadedImages;
use Illuminate\Support\Facades\Route;

Route::prefix('stock-images')->name('stock-images')->group(function () {
    Route::get('', IndexStockImages::class)->name('.index');
});

Route::prefix('uploaded-images')->name('uploaded-images')->group(function () {
    Route::get('', IndexUploadedImages::class)->name('.index');
});
