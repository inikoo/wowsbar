<?php

use App\Actions\Helpers\Uploads\DownloadUploads;
use App\Actions\Helpers\Uploads\UI\ShowUploads;
use Illuminate\Support\Facades\Route;

Route::get('{upload}/download', ['icon' => 'fa-envelope', 'label' => 'download uploads'])->uses(DownloadUploads::class)->name('download');
Route::get('{upload}', ShowUploads::class)->name('show');
