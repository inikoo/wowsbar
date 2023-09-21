<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excel_uploads', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedSmallInteger('organisation_user_id')->nullable();
            $table->foreign('organisation_user_id')->references('id')->on('organisation_users');

            $table->string('type');
            $table->string('original_filename');
            $table->string('filename');
            $table->string('path');
            $table->smallInteger('number_rows')->default(0);
            $table->dateTimeTz('uploaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_uploads');
    }
};
