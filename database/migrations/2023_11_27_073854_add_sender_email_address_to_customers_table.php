<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 29 Nov 2023 12:50:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedSmallInteger('prospects_sender_email_id')->nullable();
            $table->foreign('prospects_sender_email_id')->references('id')->on('sender_emails');
        });
    }


    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('prospects_sender_email_id');
        });
    }
};
