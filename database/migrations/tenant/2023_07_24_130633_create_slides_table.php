<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->ulid()->index()->nullable();
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedSmallInteger('snapshot_id');
            $table->boolean('visibility')->default(true)->index();
            $table->jsonb('layout')->nullable();
            $table->unsignedInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('media');
            $table->unsignedInteger('mobile_image_id')->nullable();
            $table->foreign('mobile_image_id')->references('id')->on('media');
            $table->unsignedInteger('tablet_image_id')->nullable();
            $table->foreign('tablet_image_id')->references('id')->on('media');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
