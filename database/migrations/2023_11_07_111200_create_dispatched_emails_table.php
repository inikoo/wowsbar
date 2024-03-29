<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 08:42:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Mail\DispatchedEmail\DispatchedEmailStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('dispatched_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('outbox_id')->nullable();
            $table->foreign('outbox_id')->references('id')->on('outboxes');
            $table->unsignedSmallInteger('mailshot_id')->nullable()->index();
            $table->foreign('mailshot_id')->references('id')->on('mailshots')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('email_id')->index();
            $table->foreign('email_id')->references('id')->on('emails')->onUpdate('cascade')->onDelete('cascade');
            $table->string('recipient_type')->nullable();
            $table->unsignedInteger('recipient_id')->nullable();
            $table->string('provider_message_id')->nullable()->index();
            $table->string('state')->index()->default(DispatchedEmailStateEnum::READY);
            $table->boolean('is_error')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->boolean('is_sent')->default(false);
            $table->boolean('is_delivered')->default(false);
            $table->boolean('is_hard_bounced')->default(false);
            $table->boolean('is_soft_bounced')->default(false);
            $table->boolean('is_opened')->default(false);
            $table->boolean('is_clicked')->default(false);
            $table->boolean('is_spam')->default(false);
            $table->boolean('is_unsubscribed')->default(false);
            $table->dateTimeTz('sent_at')->nullable();
            $table->dateTimeTz('delivered_at')->nullable();
            $table->dateTimeTz('date')->index();
            $table->boolean('is_test')->default(false)->index();
            $table->jsonb('data');
            $table->ulid();
            $table->timestampsTz();
            $table->index(['mailshot_id', 'is_test']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('dispatched_emails');
    }
};
