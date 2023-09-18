<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Snapshot;

use App\Actions\Portfolio\Slide\StoreSlide;
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

        return $snapshot;
    }
}
