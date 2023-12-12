<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Dec 2023 15:52:39 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->unsignedInteger('screenshot_id')->nullable();
            $table->foreign('screenshot_id')->references('id')->on('media');
            $table->boolean('is_seeded')->index()->default(false);
            $table->boolean('is_transactional')->index()->default(false);
            $table->string('type')->index();
            $table->renameColumn('title', 'name');
        });
    }


    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropForeign(['screenshot_id']);
            $table->dropColumn('screenshot_id');
            $table->dropColumn('is_seeded');
            $table->dropColumn('type');
            $table->dropColumn('is_transactional');
            $table->renameColumn('name', 'title');
        });
    }
};
