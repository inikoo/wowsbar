<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 25 Aug 2023 14:17:52 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Snapshot;

class UpdateFromFirebaseUnpublishedBannerSnapshot
{
    use WithActionUpdate;

    public function handle(Banner $banner): Banner
    {
        $tenant    =app('currentTenant');
        $database  = app('firebase.database');
        $reference = $database->getReference('tenants/' . $tenant->slug . '/banner_workshop/' . $banner->slug);
        $value     = $reference->getValue();

        $snapshot = Snapshot::find($banner->unpublished_snapshot_id);

        return UpdateUnpublishedBannerSnapshot::run($snapshot, $value);
    }
}
