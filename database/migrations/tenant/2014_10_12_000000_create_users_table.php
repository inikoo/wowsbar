<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 17:36:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->boolean('status')->default(true);
            $table->string('username')->unique()->collation('und_ns');
            $table->string('contact_name')->nullable()->collation('und_ns');
            $table->string('email')->unique()->collation('und_ns');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->unsignedSmallInteger('language_id')->default(68);
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedInteger('avatar_id')->nullable();
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
