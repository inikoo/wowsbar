<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 01 Oct 2023 00:54:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Stubs\Migrations\HasPortfolioWebsitesStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasPortfolioWebsitesStats;

    public function up(): void
    {
        Schema::create('shop_portfolios_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table=$this->portfolioWebsiteStats($table);
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('shop_portfolios_stats');
    }
};
