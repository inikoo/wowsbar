<?php

use App\Enums\Mail\DispatchedEmail\DispatchedEmailStateEnum;
use App\Enums\Mail\MailshotStateEnum;
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
        Schema::create('organisation_mailshot_stats', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations');

            $table->unsignedSmallInteger('mailshot_id')->nullable();
            $table->foreign('mailshot_id')->references('id')->on('mailshots');

            $table->unsignedBigInteger("number_mailshots")->default(0);

            foreach (MailshotStateEnum::cases() as $state) {
                $table->unsignedBigInteger("number_mailshots_state_{$state->snake()}")->default(0);
            }

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailshot_stats');
    }
};
