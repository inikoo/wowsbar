<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 23 May 2023 22:36:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('payment_service_provider_shop', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedSmallInteger('payment_service_provider_id')->index();
            $table->foreign('payment_service_provider_id')->references('id')->on('payment_service_providers');
            $table->unsignedSmallInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('public.currencies');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->unique(['shop_id', 'payment_service_provider_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payment_service_provider_shop');
    }
};
