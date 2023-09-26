<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 23 Sep 2023 09:15:16 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title');

            $table->morphs('parent');

            $table->json('data');
            $table->json('compiled');

            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
