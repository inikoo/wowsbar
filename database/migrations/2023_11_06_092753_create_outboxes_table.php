<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('outboxes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('mailroom_id')->nullable();
            $table->foreign('mailroom_id')->references('id')->on('mailrooms');
            $table->unsignedSmallInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('type')->index();
            $table->string('name');
            $table->string('state')->index()->default('in-process');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedInteger('source_id')->nullable()->unique();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('outboxes');
    }
};
