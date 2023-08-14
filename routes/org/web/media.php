<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\Media\ShowMedia;
use Illuminate\Support\Facades\Route;

Route::get('/{media:id}', ShowMedia::class)->name('show');
