<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 09:52:14 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('organisation_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organisation_stats');
    }
};
