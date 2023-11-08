<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 08:42:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Mail\EmailDeliveryStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('email_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('email_id')->index();
            $table->foreign('email_id')->references('id')->on('emails')->onUpdate('cascade')->onDelete('cascade');
            $table->string('state')->index()->default(EmailDeliveryStateEnum::READY);
            $table->dateTimeTz('sent_at')->nullable();
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('email_deliveries');
    }
};
