<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 01 Sep 2023 09:56:35 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Models\CRM\Customer;
use App\Models\Organisation\Organisation;

if (! function_exists('organisation')) {
    function organisation(): Organisation
    {
        return Organisation::firstOrFail();
    }
}

if (! function_exists('customer')) {
    function customer(): Customer
    {
        return Customer::where('id', config('global.customer_id'))->firstOrFail();
    }
}


