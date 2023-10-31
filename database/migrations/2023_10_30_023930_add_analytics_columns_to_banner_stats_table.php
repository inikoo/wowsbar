<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 20:56:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('banner_stats', function (Blueprint $table) {
            $table->after('banner_id', function () use ($table) {
                $table->unsignedInteger('number_users')->default(0);
                $table->unsignedInteger('number_clicks')->default(0);
                $table->unsignedInteger('number_views')->default(0);
            });
        });
    }


    public function down(): void
    {
        Schema::table('banner_stats', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'number_users',
                    'number_clicks',
                    'number_views'
                ]
            );
        });
    }
};
