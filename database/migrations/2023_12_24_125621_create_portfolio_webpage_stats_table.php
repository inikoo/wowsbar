<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 24 Dec 2023 20:57:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('portfolio_webpage_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('portfolio_webpage_id');
            $table->foreign('portfolio_webpage_id')->references('id')->on('portfolio_webpages');
            $table->unsignedInteger('number_of_links')->default(0);
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('portfolio_webpage_stats');
    }
};
