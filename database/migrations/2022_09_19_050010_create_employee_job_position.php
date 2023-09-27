<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 12 Sept 2022 17:56:08 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('employee_job_position', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('job_position_id')->index();
            $table->foreign('job_position_id')->references('id')->on('job_positions');
            $table->unsignedSmallInteger('employee_id')->index();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->double('share')->nullable();
            $table->timestampsTz();
            $table->unique(['job_position_id', 'employee_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('employee_job_position');
    }
};
