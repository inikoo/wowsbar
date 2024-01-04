<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 03 Jan 2024 18:13:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

use App\Enums\HumanResources\ClockingMachine\ClockingMachineTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('organisation_human_resources_stats', function (Blueprint $table) {
            $table->unsignedSmallInteger('number_clocking_machines_type_'.ClockingMachineTypeEnum::STATIC_NFC->snake())->default(0);
            $table->unsignedSmallInteger('number_clocking_machines_type_'.ClockingMachineTypeEnum::MOBILE_APP->snake())->default(0);
        });
    }


    public function down(): void
    {
        Schema::table('organisation_human_resources_stats', function (Blueprint $table) {
            $table->dropColumn('number_clocking_machines_type_'.ClockingMachineTypeEnum::STATIC_NFC->snake());
            $table->dropColumn('number_clocking_machines_type_'.ClockingMachineTypeEnum::MOBILE_APP->snake());
        });
    }
};
