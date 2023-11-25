<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 26 Nov 2023 01:53:31 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->renameColumn('scope_type', 'parent_type');
            $table->renameColumn('scope_id', 'parent_id');
        });
    }


    public function down(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->renameColumn('parent_type', 'scope_type');
            $table->renameColumn('parent_id', 'scope_id');
        });
    }
};
