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
        Schema::create('survey_question_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('survey_question_id')->index();
            $table->foreign('survey_question_id')->references('id')->on('survey_questions')->onDelete('cascade');

            $table->string('option');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_question_options');
    }
};
