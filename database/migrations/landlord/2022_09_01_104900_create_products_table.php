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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table = $this->assertCodeDescription($table);
            $table->string('type')->index();
            $table->unsignedInteger('owner_id');
            $table->string('owner_type');
            $table->unsignedInteger('parent_id');
            $table->string('parent_type');
            $table->unsignedInteger('current_historic_product_id')->index()->nullable();
            $table->unsignedSmallInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->string('state')->nullable()->index();
            $table->boolean('status')->nullable()->index();
            $table->unsignedDecimal('units', 12, 3)->nullable()->comment('units per outer');
            $table->unsignedDecimal('price', 18)->comment('unit price');
            $table->unsignedDecimal('rrp', 12, 3)->nullable()->comment('RRP per outer');
            $table->unsignedInteger('available')->default(0)->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->jsonb('settings');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedInteger('source_id')->nullable()->unique();
            $table->index(['owner_id','owner_type']);
            $table->index(['parent_id','parent_type']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
