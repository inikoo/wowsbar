<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Dec 2023 22:52:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSocketsStatisticsEntriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('websockets_statistics_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id');
            $table->integer('peak_connection_count');
            $table->integer('websocket_message_count');
            $table->integer('api_message_count');
            $table->nullableTimestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('websockets_statistics_entries');
    }
}
