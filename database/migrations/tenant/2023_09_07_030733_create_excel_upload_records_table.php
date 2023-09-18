<?php

use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excel_upload_records', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedSmallInteger('tenant_id')->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedSmallInteger('excel_upload_id');
            $table->foreign('excel_upload_id')->references('id')->on('excel_uploads')->onUpdate('cascade')->onDelete('cascade');

            $table->jsonb('data');
            $table->string('status')->default(UploadRecordStatusEnum::PROCESSING->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_upload_records');
    }
};
