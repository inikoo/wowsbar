<?php

use App\Enums\Mail\Mailshot\MailshotStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mailshots', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug');
            $table->string('subject');

            $table->unsignedSmallInteger('email_template_id')->index();
            $table->foreign('email_template_id')->references('id')->on('email_templates')->onUpdate('cascade')->onDelete('cascade');

            $table->string('state')->index()->default(MailshotStateEnum::IN_PROCESS->value);
            $table->dateTimeTz('schedule_at')->nullable();

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mailshots');
    }
};
