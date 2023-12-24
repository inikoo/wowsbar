<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:11:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\PortfolioWebpage\PortfolioWebpageStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {

        Schema::dropIfExists('portfolio_webpages');

        Schema::create('portfolio_webpages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->unsignedInteger('portfolio_website_id')->index()->nullable();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->jsonb('data');
            $table->jsonb('layout');
            $table->string('status')->default(PortfolioWebpageStatusEnum::SUCCESS);
            $table->string('message')->nullable();
            $table->string('source_slug')->index()->collation('und_ns');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['portfolio_website_id','source_slug']);
        });

    }


    public function down(): void
    {
        Schema::dropIfExists('portfolio_webpages');

    }
};
