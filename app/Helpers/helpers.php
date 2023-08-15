<?php

use App\Models\Organisation\Organisation;

if (! function_exists('organisation')) {
    function organisation(): Organisation
    {
        return Organisation::first();
    }
}
