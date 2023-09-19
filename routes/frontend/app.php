<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 09:10:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\UI\Public\Dashboard\ShowPublicDashboard;
use App\Actions\UI\Public\Appointment\ShowPublicAppointment;
use App\Actions\UI\Public\ShowHome;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'frontend',
])->group(function () {




    Route::name('public.')->group(function () {
            Route::get('/', ShowHome::class)->name('home');
            Route::get('/appointment', ShowPublicAppointment::class)->name('appointment.show');
            Route::prefix("disclosure")
                ->name("disclosure.")
                ->group(__DIR__."/disclosure.php");
        });

    Route::middleware(['public-auth:public'])->group(function () {
        Route::get('/dashboard', ShowPublicDashboard::class)->name('dashboard.show');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    require __DIR__."/auth.php";
});
