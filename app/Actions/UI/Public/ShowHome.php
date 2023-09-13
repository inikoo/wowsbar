<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 09:30:56 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class ShowHome
{
    use AsController;

    public function handle(): Response
    {
        return Inertia::render(
            'Home',
            [


                'blocks' => []


            ]
        );
    }
}
