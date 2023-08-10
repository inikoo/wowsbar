<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 09:51:40 Malaysia Time, Pantai Lembeng,, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasUserStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    use HasUserStats;
    public function up(): void
    {
        Schema::create('landlord_user_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('landlord_user_id');
            $table->foreign('landlord_user_id')->references('id')->on('landlord_users');
            $table=$this->userStatsColumns($table);
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('landlord_user_stats');
    }
};
