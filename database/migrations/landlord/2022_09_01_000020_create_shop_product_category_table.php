<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Oct 2022 18:35:32 British Summer Time, Sheffield, UK
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
        Schema::create('shop_product_category', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->string('parent_type')->index()->nullable();
            $table->unsignedSmallInteger('shop_id')->index()->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedSmallInteger('product_category_id')->index()->nullable();
            $table->foreign('product_category_id')->references('id')->on('product_categories');
            $table->timestampstz();
            $table->index(['parent_type','parent_id']);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('shop_product_category');
    }
};
