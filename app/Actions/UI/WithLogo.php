<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 08:01:45 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Models\Media\Media;

trait WithLogo
{
    public function getArt(): array
    {
        /** @var Media $logo */
        $logo = organisation()->getMedia('logo')->first();
        /** @var Media $logoWhite */
        $logoWhite = organisation()->getMedia('logo_white')->first();

        return [
            'logo'        => $logo ? GetPictureSources::run($logo->getImage()->resize(0, 64)) : null,
            'footer_logo' => $logoWhite ? GetPictureSources::run($logoWhite->getImage()->resize(0, 16)) : null,
        ];
    }
}
