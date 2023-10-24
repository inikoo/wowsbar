<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 24 Oct 2023 16:05:46 Malaysia Time, Kuala Lumpur, Malaysia
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
        Schema::create('task_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('author_id')->nullable()->comment('Employee|Guest');
            $table->string('author_type')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable()->comment('can be social post id');
            $table->string('activity_type')->nullable();
            $table->dateTimeTz('date');
            $table->unsignedSmallInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->timestampsTz();
            $table=$this->softDeletes($table);
            $table->index(['activity_id','activity_type']);
            $table->index(['author_id','author_type']);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('task_activities');
    }
};
