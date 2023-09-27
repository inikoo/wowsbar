<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 09:48:32 Malaysia Time, Pantai Lembeng, Bali
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
        Schema::create('organisation_users', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table = $this->userDetailsColumns($table, 'username');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['parent_type','parent_id']);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organisation_users');
    }
};
