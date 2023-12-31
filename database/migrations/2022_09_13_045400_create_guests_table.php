<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Stubs\Migrations\HasContact;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasContact;
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('alias')->collation('und_ns');
            $table->boolean('status')->index()->default(true);
            $table->string('type')->default(GuestTypeEnum::CONTRACTOR->value);
            $table = $this->contactFields(table: $table, withPersonalDetails: true);
            $table->jsonb('data');
            $table->timestampsTz();
            $this->softDeletes($table);
        });
        //DB::statement('CREATE INDEX ON guests USING gin (remove_accents(contact_name) gin_trgm_ops) ');
        DB::statement("CREATE INDEX ON employees (lower('alias')) ");


    }


    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
