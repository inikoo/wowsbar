<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 14:51:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('name')->index()->collation('und_ns');
            $table->string('model_type')->index();
            $table->jsonb('base');
            $table->jsonb('filters');
            $table->boolean('read_only')->index()->default(false);
            $table->timestampsTz();
            $this->softDeletes($table);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('queries');
    }
};
