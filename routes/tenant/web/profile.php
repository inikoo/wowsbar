<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 09 Sept 2022 18:32:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */


use App\Actions\UI\Profile\ShowProfile;
use App\Actions\UI\Profile\UpdateProfile;

Route::get('/', ShowProfile::class)->name('show');
Route::post('/', UpdateProfile::class)->name('update');
