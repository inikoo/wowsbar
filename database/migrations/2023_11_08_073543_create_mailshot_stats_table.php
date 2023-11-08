<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 08 Nov 2023 15:36:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasDispatchedEmailsStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasDispatchedEmailsStats;
    public function up(): void
    {
        Schema::create('mailshot_stats', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->unsignedSmallInteger('mailshot_id')->index();
            $table->foreign('mailshot_id')->references('id')->on('mailshots');

            $table=$this->dispatchedEmailsStats($table);

            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('mailshot_stats');
    }
};
