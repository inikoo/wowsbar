<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->unsignedSmallInteger('outbox_id')->nullable();
            $table->foreign('outbox_id')->references('id')->on('outboxes');
        });
    }


    public function down(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->dropColumn('outbox_id');
        });
    }
};
