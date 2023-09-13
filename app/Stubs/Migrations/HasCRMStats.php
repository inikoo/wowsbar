<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 06 Jun 2023 19:53:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\OMS\Order\OrderStateEnum;
use Illuminate\Database\Schema\Blueprint;

trait HasCRMStats
{
    public function crmStats(Blueprint $table): Blueprint
    {

        $table->unsignedInteger('number_customers')->default(0);

        foreach (CustomerStateEnum::cases() as $customerState) {
            $table->unsignedInteger("number_customers_state_{$customerState->snake()}")->default(0);
        }
        foreach (CustomerTradeStateEnum::cases() as $tradeState) {
            $table->unsignedInteger('number_customers_trade_state_'.$tradeState->snake())->default(0);
        }

        $table->unsignedInteger('number_prospects')->default(0);

        foreach (ProspectStateEnum::cases() as $prospectState) {
            $table->unsignedInteger("number_prospects_state_{$prospectState->snake()}")->default(0);
        }


        $table->unsignedInteger('number_orders')->default(0);
        foreach (OrderStateEnum::cases() as $orderState) {
            $table->unsignedInteger('number_orders_state_'.$orderState->snake())->default(0);
        }



        return $table;
    }
}
