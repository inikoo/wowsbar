<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\UI\Customer\Profile\ShowProfile;
use App\Actions\UI\Customer\Profile\UpdateProfile;

Route::get('/', ShowProfile::class)->name('show');
Route::post('/', UpdateProfile::class)->name('update');
