<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Slide;

use App\Models\Portfolio\Banner;
use App\Models\Portfolio\Slide;
use Arr;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreSlide
{
    use AsAction;


    public function handle(Banner $banner, array $modelData): Slide
    {

        $imageData=Arr::pull($modelData, 'imageData');

        data_fill($modelData, 'ulid', Str::ulid());
        /** @var Slide $slide */
        $slide= $banner->slides()->create($modelData);

        if($imageData) {
            AttachImageToSlide::run($slide, $imageData);
        }
        return $slide;
    }


}
