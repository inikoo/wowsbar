<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

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
            default => [$layout, null, null],
        };
    }

    public function parseBanner($layout): array
    {
        $slides = [];
        foreach (Arr::get($layout, 'slides') as $key => $slideLayout) {
            $slides[] = [
                'type'      => ContentBlockComponentTypeEnum::SLIDE->value,
                'layout'    => $slideLayout,
                'imageData' => Arr::get($layout, 'images')[$key]
            ];
        }

        return [
            Arr::except($layout, ['slides', 'images']),
            $slides
        ];
    }

}
