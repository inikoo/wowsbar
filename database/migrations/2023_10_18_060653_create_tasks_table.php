<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 24 Oct 2023 15:58:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;

    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('task_type_id')->index();
            $table->foreign('task_type_id')->references('id')->on('task_types')->onDelete('cascade');
            $table->unsignedSmallInteger('organisation_user_id');
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users');
            $table->unsignedInteger('activity_id')->nullable()->comment('e.g. social post id');
            $table->string('activity_type')->nullable();
            $table->dateTimeTz('date');
            $table->timestampsTz();
            $table=$this->softDeletes($table);
            $table->index(['activity_id','activity_type']);
            $table->index(['author_id','author_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
