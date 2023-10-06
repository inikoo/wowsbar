<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 06 Jun 2023 19:53:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Enums\OMS\Order\OrderStateEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

trait HasCRMStats
{
    use HasProspectStats;
    public function crmStats(Blueprint $table): Blueprint
    {

        $table->unsignedInteger('number_customers')->default(0);

        foreach (CustomerStateEnum::cases() as $customerState) {
            $table->unsignedInteger("number_customers_state_{$customerState->snake()}")->default(0);
        }


        $table->unsignedInteger('number_orders')->default(0);
        foreach (OrderStateEnum::cases() as $orderState) {
            $table->unsignedInteger('number_orders_state_'.$orderState->snake())->default(0);
        }

        $table->unsignedInteger('number_customer_websites')->default(0);

        foreach (json_decode(file_get_contents(base_path('database/seeders/datasets/divisions.json')), true) as $division) {
            $table->unsignedInteger('number_customer_websites_' . Str::snake($division['slug']))->default(0);
        }

        return $table;
    }
}
