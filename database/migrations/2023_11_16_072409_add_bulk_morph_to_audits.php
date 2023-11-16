<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 16 Nov 2023 15:32:25 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->string('bulk_type')->nullable()->index();
            $table->unsignedInteger('bulk_id')->nullable();
            $table->index(['bulk_id','bulk_type']);
        });
    }


    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn(['bulk_id','bulk_type']);
        });
    }
};
