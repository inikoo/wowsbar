<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 09 Jun 2023 03:40:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\HumanResources\TimeTracking\TimeTrackingStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('time_trackings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('status')->default(TimeTrackingStatusEnum::CREATING->value)->index();
            $table->string('subject_type')->comment('Employee|Guest');
            $table->unsignedSmallInteger('subject_id');
            $table->unsignedSmallInteger('workplace_id')->nullable()->index();
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->dateTimeTz('starts_at')->nullable();
            $table->dateTimeTz('ends_at')->nullable();
            $table->unsignedBigInteger('start_clocking_id')->nullable()->index();
            $table->foreign('start_clocking_id')->references('id')->on('clockings');
            $table->unsignedBigInteger('end_clocking_id')->nullable()->index();
            $table->foreign('end_clocking_id')->references('id')->on('clockings');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['subject_type','subject_id']);
        });

        Schema::table('clockings', function (Blueprint $table) {
            $table->foreign('time_tracking_id')->references('id')->on('time_trackings');
        });
    }


    public function down(): void
    {
        Schema::table('clockings', function (Blueprint $table) {
            $table->dropForeign('clockings_time_tracking_id_foreign');
        });
        Schema::dropIfExists('time_trackings');
    }
};
