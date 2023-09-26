<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 09:52:14 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Marketing\Shop\ShopStateEnum;
use App\Enums\Marketing\Shop\ShopTypeEnum;
use App\Enums\Organisation\OrganisationUser\OrganisationUserTypeEnum;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageStateEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
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


            $table->unsignedInteger('number_shops')->default(0);
            foreach (ShopTypeEnum::cases() as $shopType) {
                $table->unsignedSmallInteger('number_shops_type_'.$shopType->snake())->default(0);
            }
            foreach (ShopStateEnum::cases() as $shopState) {
                $table->unsignedSmallInteger('number_shops_state_'.$shopState->snake())->default(0);
            }

            $table->unsignedInteger('number_websites')->default(0);

            foreach (WebsiteStateEnum::cases() as $websiteState) {
                $table->unsignedSmallInteger('number_websites_state_'.$websiteState->snake())->default(0);
            }

            $table->unsignedInteger('number_webpages')->default(0);

            foreach (WebpageTypeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_webpages_type_'.$case->snake())->default(0);
            }
            foreach (WebpagePurposeEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_webpages_purpose_'.$case->snake())->default(0);
            }

            foreach (WebpageStateEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_webpages_state_'.$case->snake())->default(0);
            }


            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('organisation_stats');
    }
};
