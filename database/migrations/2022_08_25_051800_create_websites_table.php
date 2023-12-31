<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:42:25 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->string('type');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('code')->unique()->collation('und_ns');
            $table->string('name')->unique()->collation('und_ns');
            $table->string('state')->default(WebsiteStateEnum::IN_PROCESS->value)->index();
            $table->boolean('status')->default(false);
            $table->string('domain')->unique()->collation('und_ns');
            $table->jsonb('settings');
            $table->jsonb('data');
            $table->jsonb('layout');
            $table->jsonb('compiled_layout');
            $table->unsignedSmallInteger('unpublished_header_snapshot_id')->nullable()->index();
            $table->unsignedSmallInteger('live_header_snapshot_id')->nullable()->index();
            $table->string('published_header_checksum')->nullable()->index();
            $table->boolean('header_is_dirty')->index()->default(false);
            $table->unsignedSmallInteger('unpublished_footer_snapshot_id')->nullable()->index();
            $table->unsignedSmallInteger('live_footer_snapshot_id')->nullable()->index();
            $table->string('published_footer_checksum')->nullable()->index();
            $table->boolean('footer_is_dirty')->index()->default(false);
            $table->unsignedSmallInteger('current_layout_id')->index()->nullable();
            $table->unsignedSmallInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('logo_id')->nullable();
            $table->timestampsTz();
            $table->timestampTz('launched_at')->nullable();
            $table->timestampTz('closed_at')->nullable();
            $table=$this->softDeletes($table);
        });
        DB::statement("CREATE INDEX ON websites (lower('code')) ");
        DB::statement("CREATE INDEX ON websites (lower('domain')) ");

    }


    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
