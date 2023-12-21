<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            $table->unsignedSmallInteger('email_template_id')->nullable()->after('recipients_recipe');
            $table->jsonb('data')->nullable()->after('email_template_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mailshots', function (Blueprint $table) {
            //
        });
    }
};
