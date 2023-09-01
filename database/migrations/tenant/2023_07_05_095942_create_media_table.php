<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 18:23:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('tenant_id')->nullable()->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->string('slug')->unique()->index();
            $table->string('model_type')->nullable();
            $table->unsignedInteger('model_id')->nullable();
            $table->uuid()->nullable()->unique();
            $table->string('collection_name')->index();
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->string('checksum')->index()->nullable();
            $table->boolean('is_animated')->default(false);
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
            $table->index(['model_type','model_id']);
        });
    }
};
