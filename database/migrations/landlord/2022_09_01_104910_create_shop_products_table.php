<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 01 Sept 2022 18:55:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasAssetCodeDescription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasAssetCodeDescription;
    public function up(): void
    {
        Schema::create('product_shop', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');


            $table->unsignedSmallInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedSmallInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->string('state')->nullable()->index();
            $table->boolean('status')->nullable()->index();

            $table->unsignedDecimal('price', 18)->comment('unit price');
            $table->unsignedDecimal('rrp', 12, 3)->nullable()->comment('RRP per outer');

            $table->timestampsTz();
            $table->softDeletesTz();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('product_shop');
    }
};
