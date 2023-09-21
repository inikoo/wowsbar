<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebBlockType;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\WebBlockType;

class UpdateWebBlockType
{
    use WithActionUpdate;


    public function handle(WebBlockType $webBlockType, array $modelData): WebBlockType
    {
        return $this->update($webBlockType, $modelData, ['data']);
    }


}
