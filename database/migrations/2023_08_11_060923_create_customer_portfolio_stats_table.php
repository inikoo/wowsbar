<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:04:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Enums\Portfolio\Banner\BannerTypeEnum;
use App\Stubs\Migrations\HasBannerStats;
use App\Stubs\Migrations\HasPortfolioWebsitesStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class () extends Migration {
    use HasBannerStats;
    use HasPortfolioWebsitesStats;
    public function up(): void
    {
        Schema::create('customer_portfolio_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table=$this->portfolioWebsiteStats($table);

            $table->unsignedSmallInteger('number_stock_images')->default(0);
            foreach (BannerTypeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_stock_images_scope_' . Str::replace('-', '_', $case->snake()))->default(0);
            }

            $table->unsignedSmallInteger('number_uploaded_images')->default(0);
            foreach (BannerTypeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_uploaded_images_scope_' . Str::replace('-', '_', $case->snake()))->default(0);
            }

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_portfolio_stats');
    }
};
