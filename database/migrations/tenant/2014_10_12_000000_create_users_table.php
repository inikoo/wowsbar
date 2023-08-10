<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 17:36:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasUserDetails;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasUserDetails;

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->boolean('is_root')->index()->default(false);
            $table->unsignedSmallInteger('root_user_id')->index()->nullable();
            $table->foreign('root_user_id')->references('id')->on('root_users');
            $table = $this->userDetailsColumns($table);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['tenant_id', 'username', 'root_user_id']);
            $table->unique(['tenant_id', 'email', 'root_user_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
