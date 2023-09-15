<?php

use App\Stubs\Migrations\HasUserStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasUserStats;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('public_user_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('public_user_id');
            $table->foreign('public_user_id')->references('id')->on('public_users');
            $this->userStatsColumns($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_user_stats');
    }
};
