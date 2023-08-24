<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Slide;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\Slide;
use Arr;

class UpdateSlide
{
    use WithActionUpdate;


    public function handle(Slide $contentBlockComponent, array $modelData): Slide
    {

        $this->update($contentBlockComponent, $modelData, ['layout']);


        return $contentBlockComponent;
    }


}
