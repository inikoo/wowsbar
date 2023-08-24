<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:02:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('snapshots', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('slug')->collation('und_ns');
            $table->unsignedSmallInteger('tenant_id');
            $table->string('parent_type')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->string('state')->default(SnapshotStateEnum::UNPUBLISHED->value);
            $table->dateTimeTz('published_at')->nullable();
            $table->dateTimeTz('published_until')->nullable();
            $table->string('checksum');
            $table->jsonb('layout');
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->index(['parent_type','parent_id']);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('snapshots');
    }
};
