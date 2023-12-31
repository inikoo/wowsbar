<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 20 Oct 2022 18:35:32 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasAssetCodeDescription;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasAssetCodeDescription;
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->string('parent_type')->index()->nullable();
            $table = $this->assertCodeDescription($table);
            $table->string('type')->index();
            $table->boolean('is_family')->default(false);
            $table->string('state')->nullable()->index();
            $table->string('interest')->nullable()->index();
            $table->jsonb('data');
            $table->timestampstz();
            $table=$this->softDeletes($table);
            $table->index(['parent_type','parent_id']);
        });

        DB::statement("CREATE INDEX ON product_categories (lower('code')) ");

    }


    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
