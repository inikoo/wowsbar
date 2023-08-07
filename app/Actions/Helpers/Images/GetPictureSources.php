<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 07 Aug 2023 12:47:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Images;

use App\Helpers\ImgProxy\Exceptions\InvalidKey;
use App\Helpers\ImgProxy\Exceptions\InvalidSalt;
use App\Helpers\ImgProxy\Exceptions\MissingKey;
use App\Helpers\ImgProxy\Exceptions\MissingSalt;
use App\Helpers\ImgProxy\Image;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPictureSources
{
    use AsAction;


    public function handle(Image $image): array
    {
        $sources = [
            'webp'     => GetImgProxyUrl::run($image->extension('webp')),
            'original' => GetImgProxyUrl::run($image)
        ];


        if ($image->getWidth() or $image->getHeight()) {

            $image_2x=$image->extension('webp')->resize(
                ($image->getWidth() ?? 0) * 2,
                ($image->getHeight() ?? 0) * 2,
            );

            $sources['webp_2x']     = GetImgProxyUrl::run($image_2x);
            $sources['original_2x'] = GetImgProxyUrl::run($image_2x->extension(null));
        }

        return $sources;
    }

}
