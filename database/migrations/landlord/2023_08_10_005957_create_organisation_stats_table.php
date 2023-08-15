<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 09:52:14 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Organisation\OrganisationUser\OrganisationUserTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('organisation_stats', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onUpdate('cascade')->onDelete('cascade');


            $table->unsignedSmallInteger('number_guests')->default(0);
            $table->unsignedSmallInteger('number_guests_status_active')->default(0);
            $table->unsignedSmallInteger('number_guests_status_inactive')->default(0);

            $table->unsignedSmallInteger('number_organisation_users')->default(0);
            $table->unsignedSmallInteger('number_organisation_users_status_active')->default(0);
            $table->unsignedSmallInteger('number_organisation_users_status_inactive')->default(0);


            foreach (OrganisationUserTypeEnum::cases() as $userType) {
                $table->unsignedSmallInteger('number_organisation_users_type_'.$userType->snake())->default(0);
            }

            $table->unsignedSmallInteger('number_images')->default(0);
            $table->unsignedBigInteger('filesize_images')->default(0);
            $table->unsignedSmallInteger('number_attachments')->default(0);
            $table->unsignedBigInteger('filesize_attachments')->default(0);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organisation_stats');
    }
};
