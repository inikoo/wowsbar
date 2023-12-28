<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Dec 2023 15:37:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Support\Facades\Route;

Route::name('mobile-app.')->group(function () {
    require __DIR__."/tokens.php";
});
