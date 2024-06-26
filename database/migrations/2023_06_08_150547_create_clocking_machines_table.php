<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 09 Jun 2023 03:31:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('clocking_machines', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedBigInteger('workplace_id')->index();
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('code')->index()->collation('und_ns');
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletes();
        });
        DB::statement("CREATE INDEX ON clocking_machines (lower('code')) ");

    }


    public function down(): void
    {
        Schema::dropIfExists('clocking_machines');
    }
};
