<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 01 Nov 2023 00:32:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use Illuminate\Database\Schema\Blueprint;

trait HasCustomerUserStats
{
    public function customerUserStats(Blueprint $table): Blueprint
    {

        $table->unsignedInteger('number_customer_users')->default(0);
        $table->unsignedInteger('number_customer_users_status_active')->default(0);
        $table->unsignedInteger('number_customer_users_status_inactive')->default(0);

        return $table;
    }

    public function undoCustomerUserStats(Blueprint $table): Blueprint
    {

        $table->dropColumn('number_customer_users');
        $table->dropColumn('number_customer_users_status_active');
        $table->dropColumn('number_customer_users_status_inactive');

        return $table;
    }
}
