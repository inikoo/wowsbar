<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banner_stats', function (Blueprint $table) {
            $table->after('banner_id', function () use ($table) {
                $table->unsignedInteger('number_users')->default(0);
                $table->unsignedInteger('number_clicks')->default(0);
                $table->unsignedInteger('number_views')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_stats', function (Blueprint $table) {
            //
        });
    }
};
