<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Models\Market\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateUniversalSearch
{
    use AsAction;


    public function handle(Shop $shop): void
    {
        $shop->universalSearch()->updateOrCreate(
            [],
            [
                'shop_id'     => $shop->id,
                'section'     => 'shops',
                'title'       => trim($shop->code.' '.$shop->name),
                'description' => ''
            ]
        );
    }

}
