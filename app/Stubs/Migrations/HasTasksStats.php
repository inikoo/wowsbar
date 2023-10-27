<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 10:51:20 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Divisions\DivisionEnum;
use Illuminate\Database\Schema\Blueprint;

trait HasTasksStats
{
    public function taskStats(Blueprint $table): Blueprint
    {
        $table->unsignedInteger('number_tasks')->default(0);
        foreach (DivisionEnum::cases() as $case) {
            $table->unsignedInteger('number_tasks_division_' . $case->snake())->default(0);
        }

        return $table;
    }
}
