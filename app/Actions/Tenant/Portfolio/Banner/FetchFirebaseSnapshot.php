<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 31 Aug 2023 11:24:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

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
        $tenant    =app('currentTenant');
        $database  = app('firebase.database');
        $reference = $database->getReference('tenants/' . $tenant->slug . '/banner_workshop/' . $banner->slug);
        $value     = $reference->getValue();
        if($value){


            $modelData=[
                'layout'=>$value
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
        return 'banner:fetch-firebase {tenant} {slug}';
    }

    public function asCommand(Command $command): void
    {
        $tenant = Tenant::where('slug', $command->argument('tenant'))->firstOrFail();
        $tenant->makeCurrent();

        $banner = Banner::where('slug', $command->argument('slug'))->firstOrFail();

        $result=$this->handle($banner);
        if($result){
            $command->info("Done! banner  $banner->code unpublished slide from 🔥 updated 🥳");
        } else{
            $command->error("Banner $banner->code not found in firebase 😱");

        }
    }

}
