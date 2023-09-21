<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:36:26 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Actions\CRM\Customer\DownloadCustomersTemplate;
use App\Actions\Leads\Prospect\DownloadProspectsTemplate;

Route::get('customers', DownloadCustomersTemplate::class)->name('customers');
Route::get('prospects', DownloadProspectsTemplate::class)->name('prospects');
