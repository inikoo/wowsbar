<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:42:25 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('webpage_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('code')->index()->collation('und_ns');
            $table->unsignedInteger('webpage_id');
            $table->foreign('webpage_id')->references('id')->on('webpages')->onUpdate('cascade')->onDelete('cascade');
            $table->jsonb('components');
            $table->timestampsTz();
        });

        Schema::table('webpages', function (Blueprint $table) {
            $table->foreign('main_variant_id')->references('id')->on('webpage_variants');
        });
    }

    public function down(): void
    {
        Schema::table('webpages', function (Blueprint $table) {
            $table->dropColumn('main_variant_id');
        });
        Schema::dropIfExists('webpage_variants');
    }
};
