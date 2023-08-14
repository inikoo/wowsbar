<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasAssets;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasAssets;

    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code', 16)->unique()->collation('und_ns_ci');
            $table->string('name')->collation('und_ns_ci');
            $table->jsonb('data');
            $table->jsonb('settings');
            $table = $this->assets($table);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
