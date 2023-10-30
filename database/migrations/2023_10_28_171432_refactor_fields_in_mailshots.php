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
            $table->dateTimeTz('ready_at')->nullable();
            $table->dateTimeTz('start_sending_at')->nullable();
            $table->dateTimeTz('sent_at')->nullable();
            $table->dateTimeTz('cancelled_at')->nullable();
            $table->dateTimeTz('stopped_at')->nullable();
            $table->jsonb('layout');
            $table->jsonb('recipients');
            $table->unsignedSmallInteger('publisher_id')->nullable()->comment('org user');
            $table->foreign('publisher_id')->references('id')->on('organisation_users');
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
                'ready_at',
                'start_sending_at',
                'sent_at',
                'cancelled_at',
                'stopped_at',
                'layout',
                'recipients',
                'publisher_id',
                'scope_type',
                'scope_id',
                'deleted_at',
                'delete_comment'
            ]);
        });
    }
};
