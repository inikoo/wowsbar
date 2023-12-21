<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Dec 2023 19:12:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('portfolio_websites', function (Blueprint $table) {
            $table->string('integration')->index()->default(PortfolioWebsiteIntegrationEnum::NONE->value);
            $table->jsonb('integration_data');
        });
    }


    public function down(): void
    {
        Schema::table('portfolio_websites', function (Blueprint $table) {
            $table->dropColumn('integration');
            $table->dropColumn('integration_data');
        });
    }
};
