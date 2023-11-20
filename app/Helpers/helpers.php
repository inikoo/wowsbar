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

if(!function_exists('percentage')) {
    function percentage($a, $b, $fixed = 1, $error_txt = 'NA', $psign = '%', $plus_sign = false): string
    {
        $locale_info = localeconv();
        $per = '';
        $error_txt = _($error_txt);

        if ($b > 0) {
            if ($plus_sign && $a > 0) {
                $sign = '+';
            } else {
                $sign = '';
            }

            $per = $sign . number_format(
                    ($a / $b) * 100, $fixed, $locale_info['decimal_point'], $locale_info['thousands_sep']
                ) . $psign;
        } else {
            $per = $error_txt;
        }

        return $per;
    }
}
