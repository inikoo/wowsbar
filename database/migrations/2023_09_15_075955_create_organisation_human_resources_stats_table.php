<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 16:09:39 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Enums\HumanResources\Employee\EmployeeTypeEnum;
use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Enums\Miscellaneous\GenderEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('organisation_human_resources_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedSmallInteger('number_employees')->default(0);
            foreach (EmployeeStateEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_employees_state_'.$case->snake())->default(0);
            }
            foreach (EmployeeTypeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_employees_type_'.$case->snake())->default(0);
            }

            foreach (GenderEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_employees_gender_'.$case->snake())->default(0);
            }

            $table->unsignedSmallInteger('number_job_positions')->default(0);
            $table->unsignedSmallInteger('number_workplaces')->default(0);
            foreach (WorkplaceTypeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_workplaces_type'.$case->snake())->default(0);
            }

            $table->unsignedSmallInteger('number_clocking_machines')->default(0);
            $table->unsignedSmallInteger('number_clockings')->default(0);

            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organisation_human_resources_stats');
    }
};
