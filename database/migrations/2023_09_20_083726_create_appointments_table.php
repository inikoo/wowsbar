<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 12:33:09 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\CRM\Appointment\AppointmentStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('udn_ns')->nullable();
            $table->unsignedInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops')->onUpdate('cascade')->onDelete('cascade');

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


    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
