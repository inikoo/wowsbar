<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 24 Oct 2023 16:03:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('organisation_user_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('organisation_user_id')->index();
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users')->onDelete('cascade');
            $table->unsignedBigInteger('task_id')->index();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->timestampsTz();
            $table->index(['organisation_user_id','task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_user_tasks');
    }
};
