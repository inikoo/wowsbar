<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 14:24:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('web_block_type_id');
            $table->foreign('web_block_type_id')->references('id')->on('web_block_types')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedSmallInteger('web_block_id');
            $table->foreign('web_block_id')->references('id')->on('web_blocks')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedSmallInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->string('slug')->unique()->collation('und_ns');
            ;
            $table->string('code')->collation('und_ns_ci');
            $table->string('name')->collation('und_ns_ci');
            $table->jsonb('layout');
            $table->jsonb('data');

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
