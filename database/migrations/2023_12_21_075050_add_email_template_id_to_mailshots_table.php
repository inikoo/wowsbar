<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 08 Jan 2024 13:23:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->unsignedSmallInteger('email_template_id')->nullable()->after('recipients_recipe');
            $table->jsonb('data')->nullable()->after('email_template_id');
        });
    }

    public function down(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->dropColumn('email_template_id');
            $table->dropColumn('data');
        });
    }
};
