<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 15 Sep 2020 13:07:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('shipper_providers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('name');
            $table->jsonb('data');
            $table->timestamps();
            $this->softDeletes($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipper_providers');
    }
};
