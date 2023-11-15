<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Mar 2023 20:59:01 Malaysia Time, Kuala Lumpur, Malaysia
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
        Schema::create('outbox_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('outbox_id')->nullable();
            $table->foreign('outbox_id')->references('id')->on('outboxes');
            $table->unsignedSmallInteger('number_mailshots')->default(0);
            $table=$this->dispatchedEmailsStats($table);
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('outbox_stats');
    }
};
