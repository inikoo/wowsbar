<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 07 Aug 2023 12:47:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Images;


use App\Helpers\ImgProxy\Image;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPictureSources
{
    use AsAction;


    public function handle(Image $image): array
    {
        $sources = [
            'avif'     => GetImgProxyUrl::run($image->extension('avif')),
            'webp'     => GetImgProxyUrl::run($image->extension('webp')),
            'original' => GetImgProxyUrl::run($image)
        ];


        if ($image->getWidth() or $image->getHeight()) {

            $image_2x=$image->resize(
                ($image->getWidth() ?? 0) * 2,
                ($image->getHeight() ?? 0) * 2,
            );

            $sources['avif_2x']     = GetImgProxyUrl::run($image_2x->extension('avif'));
            $sources['webp_2x']     = GetImgProxyUrl::run($image_2x->extension('webp'));
            $sources['original_2x'] = GetImgProxyUrl::run($image_2x->extension(null));
        }

        return $sources;
    }

}
