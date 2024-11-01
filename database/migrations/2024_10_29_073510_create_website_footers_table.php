<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_footers', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');

            $table->unsignedSmallInteger('unpublished_footer_snapshot_id')->nullable()->index();
            $table->unsignedSmallInteger('live_footer_snapshot_id')->nullable()->index();

            $table->jsonb('compiled_layout');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_footers');
    }
};
