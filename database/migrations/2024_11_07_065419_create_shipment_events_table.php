<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->dateTimeTz('date', 0);
            $table->foreignId('shipment_id')->constrained();
            $table->string('box')->nullable()->index();
            $table->string('code')->nullable()->index();
            $table->unsignedSmallInteger('status')->nullable()->index();
            $table->unsignedSmallInteger('state')->nullable()->index();

            $table->jsonb('data');
            $table->timestampsTz();
            $table->unique(['date','shipment_id','box','code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
}
