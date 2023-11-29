<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 26 Nov 2023 23:29:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->boolean('can_contact_by_email')->default(false);
            $table->boolean('can_contact_by_phone')->default(false);
            $table->boolean('can_contact_by_address')->default(false);
        });
    }


    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn('can_contact_by_email');
            $table->dropColumn('can_contact_by_phone');
            $table->dropColumn('can_contact_by_address');
        });
    }
};
