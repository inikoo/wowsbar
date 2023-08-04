<?php

namespace App\Models\Traits;

use Illuminate\Database\Schema\Blueprint;

trait WithUserDetailTrait
{
    public function userDetailsColumns(Blueprint $table): void
    {
        $table->boolean('status')->default(true);
        $table->string('username')->collation('und_ns');
        $table->string('contact_name')->nullable()->collation('und_ns');
        $table->string('email')->collation('und_ns');
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->string('about')->nullable();
        $table->jsonb('data');
        $table->jsonb('settings');
        $table->unsignedSmallInteger('language_id')->default(68);
        $table->foreign('language_id')->references('id')->on('languages');
        $table->unsignedInteger('avatar_id')->nullable();
    }
}
