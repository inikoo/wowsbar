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
        Schema::create('portfolio_webpages', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->unsignedInteger('portfolio_website_id')->index()->nullable();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites')->onUpdate('cascade')->onDelete('cascade');

            $table->jsonb('data');
            $table->jsonb('layout');

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_webpages');
    }
};
