<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 14:46:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('dispatched_email_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index();
            $table->unsignedInteger('dispatched_email_id')->index()->nullable();
            $table->foreign('dispatched_email_id')->references('id')->on('dispatched_emails')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTimeTz('date')->index();
            $table->jsonb('data');
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('dispatched_email_events');
    }
};
