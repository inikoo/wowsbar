<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebBlockType;

use App\Models\Web\WebBlockType;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWebBlockType
{
    use AsAction;

    public function handle(array $modelData): WebBlockType
    {
        /** @var \App\Models\Web\WebBlockType $webBlockType */
        $webBlockType = WebBlockType::create($modelData);
        $webBlockType->stats()->create();

        return $webBlockType;
    }
}
