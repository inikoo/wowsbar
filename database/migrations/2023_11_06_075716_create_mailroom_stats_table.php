<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 16:48:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasDispatchedEmailsStats;
use App\Stubs\Migrations\HasMailshotsStats;
use App\Stubs\Migrations\HasOutboxesStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasDispatchedEmailsStats;
    use HasMailshotsStats;
    use HasOutboxesStats;

    public function up(): void
    {
        Schema::create('mailroom_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('mailroom_id')->nullable();
            $table->foreign('mailroom_id')->references('id')->on('mailrooms');

            $table=$this->outboxesStats($table);
            $table=$this->mailshotsStats($table);
            $table=$this->dispatchedEmailsStats($table);

            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('mailroom_stats');
    }
};
