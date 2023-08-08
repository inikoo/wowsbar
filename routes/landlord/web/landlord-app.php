<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 13:37:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Landlord\UI\ShowWelcome;
use App\Actions\Landlord\UI\ShowPricing;
use App\Actions\Landlord\UI\ShowWhatsNew;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', ShowWelcome::class)->name('welcome');
Route::get('/whats-new', ShowWhatsNew::class)->name('whats-new');
Route::get('/pricing', ShowPricing::class);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard/Dashboard');
})->middleware(['auth:landlord'])->name('dashboard');

Route::middleware('auth:landlord')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
