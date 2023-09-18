<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 16:09:39 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Enums\HumanResources\Employee\EmployeeTypeEnum;
use App\Enums\Miscellaneous\GenderEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('organisation_crm_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedSmallInteger('number_prospects')->default(0);

            foreach (ProspectStateEnum::cases() as $prospectState) {
                $table->unsignedSmallInteger('number_prospects_state_'.$prospectState->snake())->default(0);
            }

            foreach (GenderEnum::cases() as $gender) {
                $table->unsignedSmallInteger('number_prospects_gender_'.$gender->snake())->default(0);
            }

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_crm_stats');
    }
};
