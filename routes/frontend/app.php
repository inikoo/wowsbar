<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 17:51:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
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

    Route::middleware(['public-auth:public','public'])

        ->name('public.')->group(function () {
            Route::get('/', ShowHome::class)->name('welcome');
            Route::get('/appointment', ShowPublicAppointment::class)->name('appointment.show');
            Route::prefix("disclosure")
                ->name("disclosure.")
                ->group(__DIR__."/disclosure.php");
        });

    Route::middleware(['public-auth:public','frontend-logged-in'])->group(function () {
        Route::get('/dashboard', ShowPublicDashboard::class)->name('dashboard.show');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    require __DIR__."/auth.php";
});
