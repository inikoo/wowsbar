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
        Schema::create('mailshot_recipients', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->unsignedSmallInteger('mailshot_id')->index();
            $table->foreign('mailshot_id')->references('id')->on('mailshots')->onUpdate('cascade')->onDelete('cascade');

            $table->morphs('recipient'); // can be customer or prospect

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailshot_recipients');
    }
};
