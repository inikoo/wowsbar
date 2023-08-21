<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Media;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ImageGenerator
{
    use AsAction;

    public function handle(ActionRequest $request): array
    {
        return [
            'source' => GetPictureSources::run((new Image())->make($request->file('image')))
        ];
    }
}
