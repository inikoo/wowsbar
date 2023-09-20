<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:22:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Public\Appointment\ShowPublicAppointment;
use App\Actions\UI\Public\ShowHome;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowHome::class)->name('home');
Route::get('/appointment', ShowPublicAppointment::class)->name('appointment.show');
Route::prefix("disclosure")
    ->name("disclosure.")
    ->group(__DIR__."/disclosure.php");
