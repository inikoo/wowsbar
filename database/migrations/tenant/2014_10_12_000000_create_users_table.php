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
            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('website_id')->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->string('username')->unique()->nullable()->index()->collation('und_ns');

            $table = $this->userDetailsColumns($table, 'email');

            $table->ulid('ulid')->index()->unuque();

            $table->timestampsTz();
            $table->softDeletesTz();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
