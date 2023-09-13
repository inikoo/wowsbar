<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 13:43:08 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('payment_service_providers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('type')->index();
            $table->string('code')->index()->collation('und_ns');
            $table->string('slug')->unique()->collation('und_ns');
            $table->jsonb('data');
            $table->dateTimeTz('last_used_at')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payment_service_providers');
    }
};
