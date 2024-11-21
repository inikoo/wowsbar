<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'code')) {
                $table->dropColumn('code');
            }
            $table->string('template_code')->nullable();

        });
    }


    public function down(): void
    {
        Schema::table('announcements', function ($table) {
            $table->string('code')->nullable();
            $table->dropColumn('template_code');
        });

    }
};
