<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Dec 2023 11:30:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('mailshot_recipients', function (Blueprint $table) {
            $table->index(['recipient_id', 'recipient_type']);
        });
    }


    public function down(): void
    {
        Schema::table('mailshot_recipients', function (Blueprint $table) {
            $table->dropIndex('mailshot_recipients_recipient_id_recipient_type_index');
        });
    }
};
