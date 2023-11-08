<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 15:02:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::dropIfExists('mailshot_recipients');
        Schema::create('mailshot_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('mailshot_id')->index();
            $table->foreign('mailshot_id')->references('id')->on('mailshots')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('email_delivery_id')->index();
            $table->foreign('email_delivery_id')->references('id')->on('email_deliveries')->onUpdate('cascade')->onDelete('cascade');
            $table->string('recipient_type');
            $table->unsignedInteger('recipient_id');
            $table->unsignedSmallInteger('channel')->index();
            $table->timestampsTz();
            $table->index(['recipient_type','recipient_id','mailshot_id']);
            $table->unique(['mailshot_id','email_delivery_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mailshot_recipients');
    }
};
