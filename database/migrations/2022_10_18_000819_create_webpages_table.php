<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:42:25 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Organisation\Web\Webpage\WebpageStateEnum;
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
            $table->boolean('is_fixed')->default(false);
            $table->string('state')->index()->default(WebpageStateEnum::IN_PROCESS);
            $table->string('type')->index();
            $table->string('purpose')->index();
            $table->unsignedSmallInteger('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('webpages');
            $table->unsignedSmallInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->unsignedSmallInteger('unpublished_snapshot_id')->nullable()->index();
            $table->unsignedSmallInteger('live_snapshot_id')->nullable()->index();
            $table->jsonb('compiled_layout');
            $table->dateTimeTz('ready_at')->nullable();
            $table->dateTimeTz('live_at')->nullable();
            $table->dateTimeTz('closed_at')->nullable();
            $table->string('published_checksum')->nullable()->index();
            $table->boolean('is_dirty')->index()->default(false);
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
