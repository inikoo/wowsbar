<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 07 Nov 2023 19:39:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('slug')->unique()->after('id');
            $table->renameColumn('parent_id', 'scope_id');
            $table->renameColumn('parent_type', 'scope_type');
        });
    }


    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->renameColumn('scope_id', 'parent_id');
            $table->renameColumn('scope_type', 'parent_type');
        });
    }
};
