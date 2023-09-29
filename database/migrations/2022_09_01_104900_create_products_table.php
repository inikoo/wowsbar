<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 28 Sep 2023 00:42:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasAssetCodeDescription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasAssetCodeDescription;

    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table = $this->assertCodeDescription($table);
            $table->string('type')->index();
            $table->string('state')->nullable()->index();
            $table->boolean('status')->nullable()->index();
            $table->string('unit')->nullable();
            $table->unsignedDecimal('price', 18)->comment('unit price');
            $table->jsonb('settings');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
        DB::statement("CREATE INDEX ON products (lower('code')) ");

    }


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
