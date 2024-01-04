<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 03 Jan 2024 16:47:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('clocking_machines', function (Blueprint $table) {
            $table->string('type')->index();
            $table->renameColumn('code', 'name');
        });
    }


    public function down(): void
    {
        Schema::table('clocking_machines', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->renameColumn('name', 'code');
        });
    }
};
