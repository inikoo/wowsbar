<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 01 Sep 2023 13:04:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration
{

    public function up(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table      = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->create($table, function (Blueprint $table) {

            $morphPrefix = config('audit.user.morph_prefix', 'user');

            $table->increments('id');
            $table->string($morphPrefix . '_type')->nullable();
            $table->unsignedSmallInteger($morphPrefix . '_id')->nullable();
            $table->string('event');
            $table->morphs('auditable');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('url')->nullable();
            $table->ipAddress()->nullable();
            $table->string('user_agent', 1023)->nullable();
            $table->string('tags')->nullable();
            $table->timestampsTz();

            $table->index([$morphPrefix . '_id', $morphPrefix . '_type']);
        });
    }


    public function down(): void
    {
        $connection = config('audit.drivers.database.connection', config('database.default'));
        $table      = config('audit.drivers.database.table', 'audits');

        Schema::connection($connection)->drop($table);
    }
}
