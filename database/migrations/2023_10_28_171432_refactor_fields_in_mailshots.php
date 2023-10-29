<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 29 Oct 2023 10:29:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;

    public function up(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->string('type')->index();
            $table->dateTimeTz('date')->index();
            $table->dateTimeTz('start_sending_at')->nullable();
            if (Schema::hasColumn('mailshots', 'email_template_id')) {
                $table->dropColumn('email_template_id');
            }
            $table->string('scope_type')->index();
            $table->unsignedInteger('scope_id')->index();
            $table = $this->softDeletes($table);

            $table->index(['scope_type', 'scope_id']);
        });
    }


    public function down(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'date',
                'start_sending_at',
                'scope_type',
                'scope_type'
            ]);
        });
    }
};
