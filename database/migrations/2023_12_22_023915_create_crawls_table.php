<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 12:48:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('crawls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('portfolio_website_id');
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites');
            $table->string('type')->index();
            $table->dateTimeTz('crawled_at')->nullable();
            $table->unsignedInteger('number_of_crawled_webpages')->default(0);
            $table->unsignedInteger('number_of_new_webpages')->default(0);
            $table->unsignedInteger('number_of_updated_webpages')->default(0);
            $table->unsignedInteger('number_of_deleted_webpages')->default(0);
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('crawls');
    }
};
