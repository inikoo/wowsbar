<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 18:30:10 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('portfolio_webpages', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->unsignedInteger('portfolio_website_id')->index()->nullable();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites')->onUpdate('cascade')->onDelete('cascade');

            $table->string('title');
            $table->longText('layout');

            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('portfolio_webpages');
    }
};
