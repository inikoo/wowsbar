<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 26 Jul 2023 01:42:41 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Enums\Portfolio\ContentBlockComponent\ContentBlockComponentTypeEnum;
use App\Enums\Web\WebBlockType\WebBlockTypeSlugEnum;
use App\Models\Web\WebBlock;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class ParseContentBlockLayout
{
    use AsAction;


    public function handle(array $layout, WebBlock $webBlock): array
    {
        return match ($webBlock->slug) {
            WebBlockTypeSlugEnum::BANNER->value => $this->parseBanner($layout),
            default                             => [$layout, null, null],
        };
    }

    public function parseBanner($layout): array
    {

        $slides = [];
        foreach (Arr::get($layout, 'components') as $key => $slideData) {
            $slides[Arr::get($slideData, 'ulid', $key)] = [
                'type'          => ContentBlockComponentTypeEnum::SLIDE->value,
                'layout'        => Arr::get($slideData, 'layout'),
                'visibility'    => Arr::get($slideData, 'visibility'),
                'imageData'     => Arr::get($layout, 'images.'.$key)
            ];
        }


        return [
            Arr::except($layout, ['components', 'images']),
            $slides
        ];
    }

}
