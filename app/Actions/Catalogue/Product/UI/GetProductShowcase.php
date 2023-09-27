<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product\UI;

use App\Models\Catalogue\Product;
use Lorisleiva\Actions\Concerns\AsObject;

class GetProductShowcase
{
    use AsObject;

    public function handle(Product $product): array
    {
        return [
            []
        ];
    }
}
