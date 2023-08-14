<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Organisation\Profile\ShowProfile;
use App\Actions\UI\Organisation\Profile\UpdateProfile;

Route::get('/', ShowProfile::class)->name('show');
Route::post('/', UpdateProfile::class)->name('update');
