<?php

use App\Enums\Divisions\DivisionEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organisation_task_stats', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations');

            $table->unsignedInteger('number_task_types')->default(0);

            foreach (DivisionEnum::cases() as $case) {
                $table->unsignedInteger('number_task_types_division_' . $case->snake())->default(0);
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisation_task_stats');
    }
};
