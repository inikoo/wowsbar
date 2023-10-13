<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 08 Jul 2023 17:36:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasUserDetails;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasUserDetails;

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('email')->index()->collation('und_ns');
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table = $this->userDetailsColumns($table);
            $table->ulid('ulid')->index()->unique();
            $table->boolean('reset_password')->default(false);
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['website_id', 'email']);
        });

        DB::statement("CREATE INDEX ON users (lower('email')) ");

    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
