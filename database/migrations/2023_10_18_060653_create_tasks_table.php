<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 24 Oct 2023 15:58:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('organisation_user_id');
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users')->onDelete('cascade');
            $table->unsignedSmallInteger('task_type_id');
            $table->foreign('task_type_id')->references('id')->on('task_types')->onDelete('cascade');
            $table->dateTimeTz('date');
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
