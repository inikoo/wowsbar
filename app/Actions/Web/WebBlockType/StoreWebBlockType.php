<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 05 Jul 2023 15:42:45 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\WebBlockType;

use App\Models\Web\WebBlockType;
use Exception;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreWebBlockType
{
    use AsAction;

    /**
     * @throws Exception
     */
    public function handle(array $modelData): WebBlockType
    {

        /** @var WebBlockType $webBlockType */

        $webBlockType = WebBlockType::create($modelData);
        $webBlockType->stats()->create();
        return $webBlockType;
    }
}
