<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenancy\Tenant\Hydrators;

use App\Models\Tenancy\Tenant;
use App\Models\Portfolio\Website;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class TenantHydrateWeb implements ShouldBeUnique
{
    use AsAction;
    use HasTenantHydrate;

    public function handle(Tenant $tenant): void
    {
        $stats = [
            'number_websites' => Website::count(),
        ];

        $tenant->stats()->update($stats);
    }
}
