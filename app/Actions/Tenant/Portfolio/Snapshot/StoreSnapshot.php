<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Snapshot;

use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Snapshot;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSnapshot
{
    use AsAction;

    public function handle(Banner $parent, array $modelData): Snapshot
    {
        data_set(
            $modelData,
            'checksum',
            md5(
                json_encode(
                    Arr::get($modelData, 'layout')
                )
            )
        );

        /** @var Snapshot $snapshot */
        $snapshot = $parent->snapshots()->create($modelData);
        $snapshot->stats()->create();

        return $snapshot;
    }
}
