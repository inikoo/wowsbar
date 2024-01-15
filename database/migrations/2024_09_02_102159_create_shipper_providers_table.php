<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 15 Sep 2020 13:07:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipperProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shipper_providers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->jsonb('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('shipper_providers');
    }
}
