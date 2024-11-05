<?php

use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('portfolio_websites', function (Blueprint $table) {
            $table->after('name', function () use ($table) {
                $table->string('state')->default(PortfolioWebsiteStateEnum::IN_PROCESS->value)->index();
                $table->boolean('status')->default(false);
                $table->jsonb('settings')->nullable();
                $table->jsonb('layout')->nullable();
                $table->jsonb('compiled_layout')->nullable();
                $table->unsignedSmallInteger('unpublished_header_snapshot_id')->nullable()->index();
                $table->unsignedSmallInteger('live_header_snapshot_id')->nullable()->index();
                $table->string('published_header_checksum')->nullable()->index();
                $table->boolean('header_is_dirty')->index()->default(false);
                $table->unsignedSmallInteger('unpublished_footer_snapshot_id')->nullable()->index();
                $table->unsignedSmallInteger('live_footer_snapshot_id')->nullable()->index();
                $table->string('published_footer_checksum')->nullable()->index();
                $table->boolean('footer_is_dirty')->index()->default(false);
                $table->unsignedSmallInteger('current_layout_id')->index()->nullable();
                $table->unsignedInteger('logo_id')->nullable();
                $table->timestampTz('launched_at')->nullable();
                $table->timestampTz('closed_at')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_websites', function (Blueprint $table) {
            //
        });
    }
};
