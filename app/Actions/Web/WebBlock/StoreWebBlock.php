<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebBlock;

use App\Models\Web\WebBlock;
use App\Models\Web\WebBlockType;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWebBlock
{
    use AsAction;

    public function handle(WebBlockType $webBlockType, array $modelData): WebBlock
    {
        data_set($modelData, 'scope', $webBlockType->scope);
        /** @var \App\Models\Web\WebBlock $webBlock */
        $webBlock = $webBlockType->webBlocks()->create($modelData);
        $webBlock->stats()->create();
        return $webBlock;
    }
}
