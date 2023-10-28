<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 28 Oct 2023 12:17:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasProspectStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasProspectStats;

    public function up(): void
    {
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->prospectsStats($table);
        });
    }


    public function down(): void
    {
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->undoProspectsStats($table);

        });
    }
};
