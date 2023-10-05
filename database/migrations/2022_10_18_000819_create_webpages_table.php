<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:42:25 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('webpages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('code')->index()->collation('und_ns');
            $table->string('url')->index()->collation('und_ns');
            $table->unsignedSmallInteger('level')->index();
            $table->string('type')->index();
            $table->string('purpose')->index();

            $table->unsignedSmallInteger('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('webpages');

            $table->unsignedSmallInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->unsignedInteger('main_variant_id')->index()->nullable();
            $table->jsonb('content');
            $table->jsonb('blocks');
            $table->jsonb('compiled_content');

            $table->jsonb('data');
            $table->jsonb('settings');
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        DB::statement("CREATE INDEX ON webpages (lower('url')) ");

        Schema::table('websites', function ($table) {
            $table->unsignedInteger('home_id')->index()->nullable();
            $table->foreign('home_id')->references('id')->on('webpages')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('home_id');
        });
        Schema::dropIfExists('webpages');
    }
};
