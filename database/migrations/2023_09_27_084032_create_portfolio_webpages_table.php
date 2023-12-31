<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 18:30:10 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\PortfolioWebpage\PortfolioWebpageStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        //NOTE: All this table is dropped and recreated in the PortfolioWebsiteSeeder
        Schema::create('portfolio_webpages', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedInteger('portfolio_website_id')->index()->nullable();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->longText('layout');
            $table->string('status')->default(PortfolioWebpageStatusEnum::SUCCESS);
            $table->string('message')->nullable();
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('portfolio_webpages');
    }
};
