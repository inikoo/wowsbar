<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 24 Dec 2023 21:04:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('portfolio_website_stats', function (Blueprint $table) {
            $table->unsignedInteger('number_portfolio_webpages')->default(0);
        });
    }


    public function down(): void
    {
        Schema::table('portfolio_website_stats', function (Blueprint $table) {
            $table->dropColumn('number_portfolio_webpages');
        });
    }
};
