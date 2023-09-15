<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 19:45:17 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('country_payment_service_provider', function (Blueprint $table) {
            $table->unsignedSmallInteger('country_id')->index();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedSmallInteger('payment_service_provider_id')->index();
            $table->foreign('payment_service_provider_id')->references('id')->on('payment_service_providers');
            $table->unique(['country_id', 'payment_service_provider_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('country_payment_service_provider');
    }
};
