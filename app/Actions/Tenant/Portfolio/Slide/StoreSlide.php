<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Slide;

use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Slide;
use App\Models\Portfolio\Snapshot;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSlide
{
    use AsAction;


    public function handle(Snapshot $snapshot, array $modelData): Slide
    {


        data_fill($modelData, 'ulid', Str::ulid());
        /** @var Slide $slide */
        $slide= $snapshot->slides()->create($modelData);

        return $slide;
    }


}
