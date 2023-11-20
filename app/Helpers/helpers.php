<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 01 Sep 2023 09:56:35 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Models\CRM\Customer;
use App\Models\Organisation\Organisation;

if (!function_exists('organisation')) {
    function organisation(): Organisation
    {
        return Organisation::firstOrFail();
    }
}

if (!function_exists('customer')) {
    function customer(): Customer
    {
        return Customer::where('id', config('global.customer_id'))->firstOrFail();
    }
}

if (!function_exists('percentage')) {
    function percentage($quantity, $total, int $fixed = 1, ?string $errorMessage =null, $percentageSign = '%', $plusSing = false): string
    {
        $locale_info = localeconv();


        if ($total > 0) {
            if ($plusSing && $quantity > 0) {
                $sign = '+';
            } else {
                $sign = '';
            }

            $per = $sign.number_format(
                ($quantity / $total) * 100,
                $fixed,
                $locale_info['decimal_point'],
                $locale_info['thousands_sep']
            ).$percentageSign;
        } else {
            $per = is_null($errorMessage) ? percentage(0, 1) : $errorMessage;
        }

        return $per;
    }
}
