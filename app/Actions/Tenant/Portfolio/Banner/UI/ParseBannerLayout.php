<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:17:02 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ParseBannerLayout
{
    use AsAction;


    public function handle(array $layout): array
    {
        $slides = [];
        foreach (Arr::get($layout, 'components') as $key => $slideData) {
            $slides[Arr::get($slideData, 'ulid', $key)] = [
                'layout'        => Arr::get($slideData, 'layout'),
                'visibility'    => Arr::get($slideData, 'visibility'),
                'image_id'      => Arr::get($slideData, 'image.id')
            ];
        }

        return [
            Arr::except($layout, ['components', 'images']),
            $slides
        ];
    }


}
