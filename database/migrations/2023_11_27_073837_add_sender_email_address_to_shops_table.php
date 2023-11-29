<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 29 Nov 2023 12:48:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('sender_email_address')->nullable();
            $table->dateTimeTz('sender_email_address_validated_at')->nullable();
            $table->string('prospects_sender_email_address')->nullable();
            $table->dateTimeTz('prospects_sender_email_address_validated_at')->nullable();
        });
    }


    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('sender_email_address');
            $table->dropColumn('sender_email_address_validated_at');
            $table->dropColumn('prospects_sender_email_address');
            $table->dropColumn('prospects_sender_email_address_validated_at');
        });
    }
};
