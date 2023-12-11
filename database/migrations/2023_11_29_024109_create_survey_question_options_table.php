<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Dec 2023 15:49:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('survey_question_options', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('survey_question_id')->index();
            $table->foreign('survey_question_id')->references('id')->on('survey_questions')->onDelete('cascade');
            $table->string('option');
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('survey_question_options');
    }
};
