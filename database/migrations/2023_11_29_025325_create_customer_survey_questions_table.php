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
        Schema::create('customer_survey_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedSmallInteger('survey_question_id')->index();
            $table->foreign('survey_question_id')->references('id')->on('survey_questions')->onDelete('cascade');

            $table->string('answer_type');
            $table->string('answer')->comment('Can be string or survey question option id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_survey_questions');
    }
};
