<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 17:16:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasAssets;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasAssets;
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('code')->index();
            $table->string('name')->collation('und_ns_ci');
            $table->string('email')->collation('und_ns_ci')->index();
            $table->boolean('status')->default(true);
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->unsignedSmallInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedSmallInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->unsignedInteger('logo_id')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tenant_stats');
    }
};
