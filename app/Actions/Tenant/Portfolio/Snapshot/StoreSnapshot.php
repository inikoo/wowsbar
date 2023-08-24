<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Snapshot;

use App\Models\Portfolio\Snapshot;
use App\Models\Tenancy\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSnapshot
{
    use AsAction;

    public function handle(Tenant $tenant, array $modelData): Snapshot
    {
        return $tenant->snapshot->create($modelData);
    }
}
