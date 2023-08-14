<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code', 16)->unique()->collation('und_ns_ci');
            $table->string('name')->collation('und_ns_ci');
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->unsignedSmallInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedSmallInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedSmallInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->unsignedSmallInteger('currency_id')->comment('tenant accounting currency');
            $table->foreign('currency_id')->references('id')->on('currencies');

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
