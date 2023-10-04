<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:19:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasBannerStats;
use App\Stubs\Migrations\HasProspectStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasBannerStats;
    use HasProspectStats;
    public function up(): void
    {
        Schema::create('portfolio_website_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('portfolio_website_id')->index();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites');
            $table=$this->bannerStats($table);
            $table=$this->prospectsStats($table);
            $table->timestampsTz();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('portfolio_website_stats');
    }
};
