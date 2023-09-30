<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 01 Oct 2023 00:59:58 Malaysia Time, Kuala Lumpur, Malaysia
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
        Schema::create('organisation_portfolios_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations');
            $table=$this->portfolioWebsiteStats($table);
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organisation_portfolios_stats');
    }
};
