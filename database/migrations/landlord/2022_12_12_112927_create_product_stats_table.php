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
    public function up()
    {
        Schema::create('product_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestampsTz();
        });
    }


    public function down()
    {
        Schema::dropIfExists('product_stats');
    }
};
