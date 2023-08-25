<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:11:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('snapshot_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedMediumInteger('snapshot_id');
            $table->foreign('snapshot_id')->references('id')->on('snapshots')->onUpdate('cascade')->onDelete('cascade');
            $table->timestampsTz();
        });


    }

    public function down(): void
    {

        Schema::dropIfExists('snapshot_stats');
    }
};
