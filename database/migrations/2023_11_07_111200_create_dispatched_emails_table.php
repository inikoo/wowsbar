<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 08:42:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Mail\DispatchedEmailStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('dispatched_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('email_id')->index();
            $table->foreign('email_id')->references('id')->on('emails')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedSmallInteger('mailshot_id')->nullable()->index();
            $table->foreign('mailshot_id')->references('id')->on('mailshots')->onUpdate('cascade')->onDelete('cascade');
            $table->string('provider_message_id')->nullable()->index();
            $table->string('state')->index()->default(DispatchedEmailStateEnum::READY);
            $table->boolean('is_sent')->default(false);
            $table->boolean('is_delivered')->default(false);
            $table->boolean('is_open')->default(false);
            $table->boolean('is_clicked')->default(false);
            $table->boolean('is_throttled')->default(false);
            $table->dateTimeTz('sent_at')->nullable();
            $table->dateTimeTz('delivered_at')->nullable();
            $table->dateTimeTz('date')->index();
            $table->boolean('is_test')->default(false);
            $table->jsonb('data');
            $table->ulid();
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('dispatched_emails');
    }
};
