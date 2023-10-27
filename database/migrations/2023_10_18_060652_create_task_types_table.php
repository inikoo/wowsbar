<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 24 Oct 2023 15:55:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('task_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedBigInteger('division_id')->index();
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('name')->unique()->collation('und_ns_ci');
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_types');
    }
};
