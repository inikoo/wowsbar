<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\UI\Organisation\Website\ShowWebsiteDashboard;

use Illuminate\Support\Facades\Route;

Route::get('/', ShowWebsiteDashboard::class)->name('dashboard');
