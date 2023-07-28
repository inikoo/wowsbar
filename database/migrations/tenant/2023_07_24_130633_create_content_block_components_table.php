<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('content_block_components', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->ulid()->index()->nullable();
            $table->string('type')->index();
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->unsignedSmallInteger('content_block_id');
            $table->foreign('content_block_id')->references('id')->on('content_blocks')->onUpdate('cascade')->onDelete('cascade');
            $table->jsonb('layout')->nullable();
            $table->unsignedInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('media');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('content_block_components');
    }
};
