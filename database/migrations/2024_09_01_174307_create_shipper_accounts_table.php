<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipperAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shipper_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->index();
            $table->string('label')->nullable();
            $table->unsignedSmallInteger('shipper_id');
            $table->foreign('shipper_id')->references('id')->on('shippers');
            $table->unsignedSmallInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->json('credentials');
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
        Schema::dropIfExists('shipper_accounts');
    }
}
