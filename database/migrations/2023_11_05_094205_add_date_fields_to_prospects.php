<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Nov 2023 10:49:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dateTimeTz('last_contacted_at')->nullable()->index();
            $table->dateTimeTz('not_interested_at')->nullable();
            $table->dateTimeTz('registered_at')->nullable();
            $table->dateTimeTz('invoiced_at')->nullable();
            $table->dateTimeTz('last_bounced_at')->nullable();

        });
    }


    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn([
                'last_contacted_at',
                'not_interested_at',
                'registered_at',
                'invoiced_at',
                'last_bounced_at',
            ]);
        });
    }
};
