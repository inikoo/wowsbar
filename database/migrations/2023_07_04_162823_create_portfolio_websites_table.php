<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 12 Oct 2022 17:36:20 Central European Summer Time, Benalmádena, Malaga Spain
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('portfolio_websites', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('slug')->collation('und_ns');
            $table->string('code')->collation('und_ns');
            $table->string('domain')->collation('und_ns');
            $table->string('name')->collation('und_ns');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['customer_id','slug']);
            $table->unique(['customer_id','code']);
            $table->unique(['customer_id','domain']);
        });
        DB::statement("CREATE INDEX ON portfolio_websites (lower('code')) ");
        DB::statement("CREATE INDEX ON portfolio_websites (lower('domain')) ");

    }


    public function down(): void
    {
        Schema::dropIfExists('portfolio_websites');
    }
};
