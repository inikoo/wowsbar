<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 17:27:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Divisions\DivisionEnum;
use App\Stubs\Migrations\HasTasksStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasTasksStats;
    public function up(): void
    {
        Schema::create('organisation_task_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations');
            $table->unsignedInteger('number_divisions')->default(0);
            $table->unsignedInteger('number_task_types')->default(0);
            foreach (DivisionEnum::cases() as $case) {
                $table->unsignedInteger('number_task_types_division_' . $case->snake())->default(0);
            }
            $table=$this->taskStats($table);
            $table->timestampsTz();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organisation_task_stats');
    }
};
