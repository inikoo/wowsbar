<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 02 Sep 2020 03:38:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shippers', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->index();
            $table->string('name')->nullable();
            $table->unsignedSmallInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedSmallInteger('provider_id')->index()->nullable();
            $table->string('provider_type')->index()->nullable();
            $table->jsonb('data');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('shippers');
    }
}
