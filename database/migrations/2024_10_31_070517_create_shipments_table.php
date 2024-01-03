<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 31 Oct 2020 15:05:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipper_account_id')->constrained();
            $table->string('status')->default('processing')->index();
            $table->string('reference')->nullable()->index();
            $table->string('tracking')->nullable()->index();
            $table->string('error_message')->nullable()->index();

            $table->string('min_state')->nullable()->index();
            $table->string('max_state')->nullable()->index();
            $table->dateTimeTz('tracked_at')->nullable()->index();
            $table->unsignedSmallInteger('tracked_count')->default(0);

            $table->jsonb('data');

            $table->timestampsTz();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
}
