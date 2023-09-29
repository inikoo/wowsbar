<?php

use App\Enums\CRM\Appointment\AppointmentStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedSmallInteger('organisation_user_id')->nullable();
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users');

            $table->dateTimeTz('schedule_at');
            $table->string('description')->nullable();

            $table->string('state')->default(AppointmentStateEnum::BOOKED->value);
            $table->string('type');

            $table->string('event');
            $table->string('event_address');

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
