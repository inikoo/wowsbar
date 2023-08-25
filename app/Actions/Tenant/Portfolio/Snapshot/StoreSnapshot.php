<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Snapshot;

use App\Actions\Tenant\Portfolio\Slide\StoreSlide;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Snapshot;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSnapshot
{
    use AsAction;

    public function handle(Banner $banner, array $modelData, ?array $slides): Snapshot
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


        $snapshot=Snapshot::create($modelData);


        $banner->snapshots()->save($snapshot);
        $banner->generateSlug();
        if ($slides) {
            foreach ($slides as $slide) {
                StoreSlide::run(
                    snapshot: $snapshot,
                    modelData: $slide,
                );
            }
        }

        $snapshot->stats()->create();

        return $snapshot;
    }
}
