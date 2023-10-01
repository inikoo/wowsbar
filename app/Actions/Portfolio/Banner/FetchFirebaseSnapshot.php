<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Actions\Traits\WithActionUpdate;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Lorisleiva\Actions\ActionRequest;

class FetchFirebaseSnapshot
{
    use WithActionUpdate;

    public function handle(Banner $banner): bool
    {
        $customer  = customer();
        $database  = app('firebase.database');
        $reference = $database->getReference('tenants/'.$customer->slug.'/banner_workshop/'.$banner->slug);
        $value     = $reference->getValue();
        if ($value) {
            $modelData = [
                'layout' => $value
            ];

            UpdateUnpublishedBannerSnapshot::run($banner->unpublishedSnapshot, $modelData);

            return true;
        }

        return false;
    }


    public function asController(Banner $banner, ActionRequest $request): bool
    {
        $request->validate();

        return $this->handle($banner);
    }

    public function getCommandSignature(): string
    {
        return 'banner:fetch-firebase {customer} {slug}';
    }

    public function asCommand(Command $command): void
    {
        $customer = Customer::where('slug', $command->argument('customer'))->firstOrFail();

        Config::set('global.customer_id', $customer->id);

        $banner = Banner::where('slug', $command->argument('slug'))->firstOrFail();

        $result = $this->handle($banner);
        if ($result) {
            $command->info("Done! banner  $banner->slug unpublished slide from 🔥 updated 🥳");
        } else {
            $command->error("Banner $banner->slug not found in firebase 😱");
        }
    }

}
