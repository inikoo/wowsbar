<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\Banner;
use App\Models\Tenancy\Tenant;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;

class FetchFirebaseSnapshot
{
    use WithActionUpdate;

    public function handle(Banner $banner): bool
    {
        $tenant    =customer();
        $database  = app('firebase.database');
        $reference = $database->getReference('tenants/' . $tenant->slug . '/banner_workshop/' . $banner->slug);
        $value     = $reference->getValue();
        if($value) {


            $modelData=[
                'layout'=> $value
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
        $tenant = Tenant::where('slug', $command->argument('customer'))->firstOrFail();
        $tenant->makeCurrent();

        $banner = Banner::where('slug', $command->argument('slug'))->firstOrFail();

        $result=$this->handle($banner);
        if($result) {
            $command->info("Done! banner  $banner->code unpublished slide from 🔥 updated 🥳");
        } else {
            $command->error("Banner $banner->code not found in firebase 😱");

        }
    }

}
