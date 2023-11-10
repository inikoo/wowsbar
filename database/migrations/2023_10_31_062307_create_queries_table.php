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
            $table->smallIncrements('id');
            $table->string('scope_type')->index();
            $table->unsignedInteger('scope_id')->index();
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('name')->index()->collation('und_ns');
            $table->string('model_type')->index();
            $table->jsonb('constrains');
            $table->jsonb('arguments');
            $table->boolean('is_seeded')->index()->default(false);
            $table->unsignedInteger('number_items')->nullable();
            $table->dateTimeTz('counted_at')->nullable();
            $table->timestampsTz();
            $this->softDeletes($table);
            $table->index(['scope_type','scope_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queries');
    }
};
