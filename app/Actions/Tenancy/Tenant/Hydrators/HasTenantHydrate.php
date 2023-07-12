<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:19 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\Tenant\Hydrators;

use App\Models\Tenancy\Tenant;

trait HasTenantHydrate
{
    public function getJobUniqueId(Tenant $tenant): string
    {
        return $tenant->id;
    }

    public function getJobTags(): array
    {
        /** @var \App\Models\Tenancy\Tenant $tenant */
        $tenant=app('currentTenant');
        return ['central','tenant:'.$tenant->slug];
    }


}
