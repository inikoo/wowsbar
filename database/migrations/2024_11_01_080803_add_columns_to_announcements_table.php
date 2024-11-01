<?php

use App\Enums\Portfolio\Announcement\AnnouncementStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->after('published_checksum', function ($table) {
                $table->string('schedule_at')->nullable();
                $table->string('schedule_finish_at')->nullable();
                $table->string('status')->default(AnnouncementStatusEnum::INACTIVE->value);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            //
        });
    }
};
