<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Snapshot;

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
