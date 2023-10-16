<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Reviewed: Sat, 14 Oct 2023 09:46:58 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('portfolio_social_accounts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->collation('und_ns')->nullable();
            $table->string('username');
            $table->string('url')->nullable();
            $table->string('platform')->index();
            $table->unsignedBigInteger('number_followers')->default(0);
            $table->unsignedBigInteger('number_posts')->default(0);
            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedSmallInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->jsonb('data');
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('portfolio_social_accounts');
    }
};
