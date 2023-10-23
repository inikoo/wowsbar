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
        Schema::create('organisation_user_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organisation_user_id');
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users')->onDelete('cascade');

            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisation_user_tasks');
    }
};
