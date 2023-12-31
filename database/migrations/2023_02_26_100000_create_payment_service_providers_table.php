<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 13:43:08 Malaysia Time, Pantai Lembeng, Bali, Indonesia
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
        Schema::create('payment_service_providers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('type')->index();
            $table->string('code')->unique()->index()->collation('und_ns');
            $table->string('name')->index()->collation('und_ns');
            $table->string('url')->nullable();
            $table->boolean('show_marketplace')->nullable()->index();
            $table->jsonb('data');
            $table->dateTimeTz('last_used_at')->nullable();
            $table->timestampsTz();
            $table=$this->softDeletes($table);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payment_service_providers');
    }
};
