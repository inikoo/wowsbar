<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 17:36:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Models\Traits\WithUserDetailTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use WithUserDetailTrait;
    public function up(): void
    {
        Schema::create('root_users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');

            $this->userDetailsColumns($table);

            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['tenant_id','username']);
            $table->unique(['tenant_id','email']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
