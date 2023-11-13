<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:22:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\CRM\Appointment\CheckCustomerAppointment;
use App\Actions\CRM\Appointment\GetBookedScheduleAppointment;
use App\Actions\CRM\Appointment\LoginCustomerAppointment;
use App\Actions\UI\Public\Appointment\ShowPublicAppointment;
use App\Actions\UI\Public\ShowHome;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHome::class)->name('home');

Route::prefix('appointment')->as('appointment.')->group(function () {
    Route::get('/', ShowPublicAppointment::class)->name('show');
    Route::get('/schedule', GetBookedScheduleAppointment::class)->name('schedule');
    Route::post('/check/email', CheckCustomerAppointment::class)->name('check.email');
    Route::post('/login', LoginCustomerAppointment::class)->name('login');
});

Route::prefix("disclosure")
    ->name("disclosure.")
    ->group(__DIR__."/disclosure.php");

require __DIR__."/auth.php";
