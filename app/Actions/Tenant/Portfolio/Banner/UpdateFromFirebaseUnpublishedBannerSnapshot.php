<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 25 Aug 2023 14:17:52 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateBanners;
use App\Actions\Tenant\Portfolio\Banner\Hydrators\BannerHydrateUniversalSearch;
use App\Actions\Tenant\Portfolio\Banner\UI\ParseBannerLayout;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\Hydrators\PortfolioWebsiteHydrateBanners;
use App\Actions\Tenant\Portfolio\Slide\StoreSlide;
use App\Actions\Tenant\Portfolio\Slide\UpdateSlide;
use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Slide;
use App\Models\Portfolio\Snapshot;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;

class UpdateFromFirebaseUnpublishedBannerSnapshot
{
    use WithActionUpdate;

    public function handle(Banner $banner): Banner
    {
        $tenant =app('currentTenant');
        $database = app('firebase.database');
        $reference = $database->getReference('tenants/' . $tenant->slug . '/banner_workshop/' . $banner->slug);
        $value = $reference->getValue();

        $snapshot = Snapshot::find($banner->unpublished_snapshot_id);

        return UpdateUnpublishedBannerSnapshot::run($snapshot, $value);
    }
}
