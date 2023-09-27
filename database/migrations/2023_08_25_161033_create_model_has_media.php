<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 26 Aug 2023 23:14:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('model_has_media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_type');
            $table->unsignedInteger('model_id');
            $table->unsignedMediumInteger('media_id')->index();
            $table->foreign('media_id')->references('id')->on('media')->onUpdate('cascade')->onDelete('cascade');
            $table->smallInteger('position')->default(1);
            $table->string('scope')->index();
            $table->timestampsTz();
            $table->unique(['model_type','model_id','media_id','scope']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshot_stats');
    }
};
