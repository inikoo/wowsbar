<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 13 Nov 2023 15:43:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Mail\MailshotSendChannelStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('mailshot_send_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('mailshot_id')->nullable()->index();
            $table->foreign('mailshot_id')->references('id')->on('mailshots')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedSmallInteger('number_emails');
            $table->string('state')->default(MailshotSendChannelStateEnum::READY->value);
            $table->dateTimeTz('start_sending_at')->nullable();
            $table->dateTimeTz('sent_at')->nullable();

            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('mailshot_send_channels');
    }
};
