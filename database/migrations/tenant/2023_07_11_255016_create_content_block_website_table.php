<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 15:19:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('content_block_website', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->ulid()->index();
            $table->unsignedInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedSmallInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('content_block_id')->index();
            $table->foreign('content_block_id')->references('id')->on('content_blocks')->onUpdate('cascade')->onDelete('cascade');
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('content_block_website');
    }
};
