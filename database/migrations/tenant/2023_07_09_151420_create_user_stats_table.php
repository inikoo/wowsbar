<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 09 Jul 2023 23:15:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('number_logins')->default(0);
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();

            $table->datetime('last_active_at')->nullable();

            $table->unsignedInteger('number_failed_logins')->default(0);
            $table->string('last_failed_login_ip')->nullable();
            $table->datetime('last_failed_login_at')->nullable();

            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('user_stats');
    }
};
