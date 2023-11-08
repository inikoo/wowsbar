<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 15:42:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Mail\EmailDeliveryStateEnum;
use Illuminate\Database\Schema\Blueprint;

trait HasDispatchedEmailsStats
{
    public function dispatchedEmailsStats(Blueprint $table): Blueprint
    {

        $table->unsignedInteger('number_email_deliveries')->default(0);
        foreach (EmailDeliveryStateEnum::cases() as $case) {
            $table->unsignedInteger("number_email_deliveries_state_{$case->snake()}")->default(0);
        }

        return $table;
    }

    public function undoDispatchedEmailsStats(Blueprint $table): Blueprint
    {
        $table->dropColumn('number_email_deliveries');
        foreach (EmailDeliveryStateEnum::cases() as $case) {
            $table->dropColumn("number_email_deliveries_state_{$case->snake()}");
        }


        return $table;
    }
}
