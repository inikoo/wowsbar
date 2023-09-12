<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:57:40 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use Illuminate\Database\Schema\Blueprint;

trait HasUserDetails
{
    public function userDetailsColumns(Blueprint $table, string $usernameField): Blueprint
    {

        if($usernameField=='username') {
            $table->string('username')->index()->collation('und_ns');
        }

        $table->boolean('status')->default(true);
        $table->string('contact_name')->nullable()->collation('und_ns');

        if($usernameField=='email') {
            $table->string('email')->index()->unique()->collation('und_ns');
            $table->timestamp('email_verified_at')->nullable();
        } else {
            $table->string('email')->index()->nullable()->collation('und_ns');
        }


        $table->string('password');
        $table->rememberToken();
        $table->string('about')->nullable();
        $table->jsonb('data');
        $table->jsonb('settings');
        $table->unsignedSmallInteger('language_id')->default(68);
        $table->foreign('language_id')->references('id')->on('languages');
        $table->unsignedInteger('avatar_id')->nullable();

        return $table;
    }
}
