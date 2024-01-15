<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Dec 2023 23:41:31 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\UI\Organisation\Profile\ShowProfile;
use App\Actions\UI\Organisation\Profile\UpdateProfile;

Route::get('/', ShowProfile::class)->name('show');
Route::post('/', UpdateProfile::class)->name('update');
