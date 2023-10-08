<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('customer_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('udn_ns')->nullable();
            $table->boolean('is_root')->default(false)->index();
            $table->boolean('status')->default(true);
            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestampsTz();
            $table->unique(['customer_id', 'user_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('customer_user');
    }
};
