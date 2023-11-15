<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Mail\Outbox\OutboxStateEnum;
use App\Enums\Mail\Outbox\OutboxTypeEnum;
use Illuminate\Database\Schema\Blueprint;

trait HasOutboxesStats
{
    public function outboxesStats(Blueprint $table): Blueprint
    {
        $table->unsignedSmallInteger('number_outboxes')->default(0);
        foreach (OutboxTypeEnum::cases() as $outboxTypeEnum) {
            $table->unsignedInteger('number_outbox_type_'.$outboxTypeEnum->snake())->default(0);
        }
        foreach (OutboxStateEnum::cases() as $outboxStateEnum) {
            $table->unsignedInteger('number_outbox_state_'.$outboxStateEnum->snake())->default(0);
        }


        return $table;
    }
}
