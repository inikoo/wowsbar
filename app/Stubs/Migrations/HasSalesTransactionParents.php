<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 26 May 2023 15:48:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use Illuminate\Database\Schema\Blueprint;

trait HasSalesTransactionParents
{
    public function salesTransactionParents(Blueprint $table): Blueprint
    {
        $table->unsignedInteger('customer_id')->index();
        $table->foreign('customer_id')->references('id')->on('customers');

        return $table;
    }
}
