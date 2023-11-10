<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 13:22:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->jsonb('channels')->nullable();
        });
        Schema::table('mailshots', function (Blueprint $table) {
            DB::table('mailshots')->whereNull('channels')->update(['channels'=>'{}']);
        });
    }


    public function down(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->dropColumn('channels');
        });
    }
};
