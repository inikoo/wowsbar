<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Snapshot;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\Snapshot;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSnapshot
{
    use AsAction;
    use WithActionUpdate;

    public function handle(Snapshot $snapshot, array $modelData): Snapshot
    {
        $this->update($snapshot, $modelData, ['layout']);

        return $snapshot;
    }
}
