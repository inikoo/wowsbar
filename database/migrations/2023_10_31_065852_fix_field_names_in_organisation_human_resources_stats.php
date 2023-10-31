<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 17:23:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('organisation_human_resources_stats', function (Blueprint $table) {
            foreach (WorkplaceTypeEnum::cases() as $case) {
                $table->dropColumn('number_workplaces_type'.$case->snake());
            }
            foreach (WorkplaceTypeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_workplaces_type_'.$case->snake())->default(0);
            }
        });
    }


    public function down(): void
    {
        Schema::table('organisation_human_resources_stats', function (Blueprint $table) {
            foreach (WorkplaceTypeEnum::cases() as $case) {
                $table->dropColumn('number_workplaces_type_'.$case->snake());
            }
            foreach (WorkplaceTypeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_workplaces_type'.$case->snake())->default(0);
            }
        });
    }
};
