<?php

use App\Actions\Helpers\Uploads\UI\IndexExcelUploadRecords;

Route::prefix('uploads')->as('uploads.')->group(function () {
    Route::get('records', IndexExcelUploadRecords::class)->name('records.index');
});
