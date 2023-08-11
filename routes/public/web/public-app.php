<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:22:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\UI\Public\Dashboard\ShowPublicDashboard;
use App\Actions\UI\Public\ShowPricing;
use App\Actions\UI\Public\ShowWelcome;
use App\Actions\UI\Public\ShowWhatsNew;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    "public-web",
])->group(function () {
    Route::get('/', ShowWelcome::class)->name('welcome');
    Route::get('/whats-new', ShowWhatsNew::class)->name('whats-new');
    Route::get('/pricing', ShowPricing::class)->name('pricing');
    Route::middleware(["auth:public"])->group(function () {
        Route::get('/dashboard', ShowPublicDashboard::class)->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    require __DIR__."/public-auth.php";
});
