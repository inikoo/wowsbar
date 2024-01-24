<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 22 Jan 2024 12:00:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

use App\Enums\HumanResources\TimeTracking\TimeTrackingStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('time_trackings', function (Blueprint $table) {
            $table->string('status')->default(TimeTrackingStatusEnum::IN->value)->change();
        });
    }


    public function down(): void
    {

    }
};
