<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:02:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('snapshots', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('slug')->unique()->collation('und_ns')->nullable();
            $table->string('scope')->index()->nullable();
            $table->string('publisher_type')->nullable();
            $table->unsignedSmallInteger('publisher_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('state')->default(SnapshotStateEnum::UNPUBLISHED->value);
            $table->dateTimeTz('published_at')->nullable();
            $table->dateTimeTz('published_until')->nullable();
            $table->string('checksum')->index();
            $table->jsonb('layout');
            $table->string('comment')->nullable();
            $table->boolean('first_commit')->default(false);
            $table->boolean('recyclable')->nullable();
            $table->string('recyclable_tag')->nullable();
            $table->timestampsTz();
            $table->index(['parent_type', 'parent_id']);
            $table->index(['parent_type', 'parent_id', 'scope']);
            $table->index(['publisher_id', 'publisher_type']);
        });

        Schema::table('websites', function (Blueprint $table) {
            $table->foreign('unpublished_header_snapshot_id')->references('id')->on('snapshots');
            $table->foreign('live_header_snapshot_id')->references('id')->on('snapshots');
            $table->foreign('unpublished_footer_snapshot_id')->references('id')->on('snapshots');
            $table->foreign('live_footer_snapshot_id')->references('id')->on('snapshots');
        });
        Schema::table('banners', function (Blueprint $table) {
            $table->foreign('unpublished_snapshot_id')->references('id')->on('snapshots');
            $table->foreign('live_snapshot_id')->references('id')->on('snapshots');
        });
        Schema::table('slides', function (Blueprint $table) {
            $table->foreign('snapshot_id')->references('id')->on('snapshots')->onUpdate('cascade')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropForeign(['snapshot_id']);
        });
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign(['live_snapshot_id', 'unpublished_snapshot_id']);
        });
        Schema::table('websites', function (Blueprint $table) {
            $table->dropForeign(['live_header_snapshot_id', 'unpublished_header_snapshot_id', 'live_footer_snapshot_id', 'unpublished_footer_snapshot_id']);
        });
        Schema::dropIfExists('snapshots');
    }
};
