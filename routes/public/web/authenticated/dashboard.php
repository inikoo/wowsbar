<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 11:06:03 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowDashboard::class)->name('show');
