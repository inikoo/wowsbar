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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organisation_user_id');
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users')->onDelete('cascade');
            $table->unsignedBigInteger('task_type_id');
            $table->foreign('task_type_id')->references('id')->on('task_types')->onDelete('cascade');
            $table->dateTimeTz('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
