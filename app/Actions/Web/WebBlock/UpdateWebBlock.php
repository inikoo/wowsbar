<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebBlock;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\WebBlock;

class UpdateWebBlock
{
    use WithActionUpdate;


    public function handle(WebBlock $webBlock, array $modelData): WebBlock
    {
        return $this->update($webBlock, $modelData, ['data']);
    }


}
