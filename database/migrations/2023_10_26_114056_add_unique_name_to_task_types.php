<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 19:41:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('task_types', function (Blueprint $table) {
            $table->unique('name');
        });
    }


    public function down(): void
    {
        Schema::table('task_types', function (Blueprint $table) {
            $table->dropUnique('task_types_name_unique');
        });
    }
};
