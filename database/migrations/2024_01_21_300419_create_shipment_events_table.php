<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 16 Jan 2024 11:49:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->dateTimeTz('date');
            $table->unsignedInteger('shipment_id');
            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->string('box')->nullable()->index();
            $table->string('code')->nullable()->index();
            $table->unsignedSmallInteger('status')->nullable()->index();
            $table->unsignedSmallInteger('state')->nullable()->index();
            $table->jsonb('data');
            $table->timestampsTz();
            $table= $this->softDeletes($table);
            $table->unique(['date','shipment_id','box','code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
