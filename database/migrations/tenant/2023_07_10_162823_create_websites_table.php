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
        Schema::create('websites', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->string('slug')->collation('und_ns');
            $table->string('code')->collation('und_ns');
            $table->string('domain')->collation('und_ns');
            $table->string('name')->collation('und_ns');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['tenant_id','slug']);
            $table->unique(['tenant_id','code']);
            $table->unique(['tenant_id','domain']);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
