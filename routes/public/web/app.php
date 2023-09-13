<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:22:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Accounting\PaymentGateway\Xendit\Webhook\HandleWebhookNotification;
use App\Actions\UI\Public\Dashboard\ShowPublicDashboard;
use App\Actions\UI\Public\ShowHome;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    "public-web",
])->group(function () {
    Route::get('/', ShowHome::class)->name('welcome');

    Route::prefix("disclosure")
        ->name("disclosure.")
        ->group(__DIR__."/disclosure.php");


    Route::middleware(["public-auth:public"])->group(function () {
        Route::get('/dashboard', ShowPublicDashboard::class)->name('dashboard.show');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::post('xendit/callback', HandleWebhookNotification::class)->name('webhook.xendit.notification');
    require __DIR__."/auth.php";
});
