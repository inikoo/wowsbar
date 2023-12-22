<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:11:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('portfolio_webpages', function (Blueprint $table) {
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('source_id')->index();
            $table->unique(['portfolio_website_id','source_id']);
        });
    }


    public function down(): void
    {
        Schema::table('portfolio_webpages', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('source_id');
            $table->dropUnique(['portfolio_website_id','source_id']);

        });
    }
};
