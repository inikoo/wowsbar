<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 12 Dec 2022 19:37:02 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('shop_product_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_product_id')->index();
            $table->foreign('shop_product_id')->references('id')->on('shop_product');
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_product_stats');
    }
};
