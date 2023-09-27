<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 19:30:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Helpers\Import\UploadRecordStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('upload_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('upload_id');
            $table->foreign('upload_id')->references('id')->on('uploads')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('row_number')->nullable();
            $table->jsonb('values');
            $table->jsonb('errors');
            $table->string('fail_column')->nullable();
            $table->string('status')->default(UploadRecordStatusEnum::PROCESSING->value);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('upload_records');
    }
};
