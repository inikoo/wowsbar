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
        Schema::create('product_shop_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_shop_id')->index();
            $table->foreign('product_shop_id')->references('id')->on('product_shop');
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('product_shop_stats');
    }
};