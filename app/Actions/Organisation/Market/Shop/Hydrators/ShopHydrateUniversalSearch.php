<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 11 Mar 2023 16:06:25 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Market\Shop\Hydrators;

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
                'section'     => 'shops',
                'title'       => trim($shop->code.' '.$shop->name),
                'description' => ''
            ]
        );
    }

}
