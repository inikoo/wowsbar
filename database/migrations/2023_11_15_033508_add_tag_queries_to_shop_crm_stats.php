<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 15:02:52 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $table->unsignedSmallInteger('number_prospect_queries')->default(0);
            $table->unsignedSmallInteger('number_customer_queries')->default(0);
        });
        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $table->unsignedSmallInteger('number_tags')->default(0);
        });
    }


    public function down(): void
    {
        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $table->dropColumn('number_tags');
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $table->dropColumn(['number_prospect_queries', 'number_customer_queries']);
        });
    }
};
