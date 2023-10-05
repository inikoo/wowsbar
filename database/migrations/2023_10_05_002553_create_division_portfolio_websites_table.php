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
        Schema::create('division_portfolio_websites', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->unsignedSmallInteger('division_id')->index();
            $table->foreign('division_id')->references('id')->on('divisions');

            $table->unsignedSmallInteger('portfolio_website_id')->index();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites');

            $table->string('interest');

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_portfolio_websites');
    }
};
