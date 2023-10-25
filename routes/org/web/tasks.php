<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:11:13 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Actions\Subscriptions\CustomerWebsite\UI\IndexSocialCustomerWebsites;
use App\Actions\UI\Organisation\Catalogue\ShowSocialDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('/social/dashboard');});
