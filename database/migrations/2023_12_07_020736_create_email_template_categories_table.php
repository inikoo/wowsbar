<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Dec 2023 15:51:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('email_template_categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('email_template_categories');
    }
};
