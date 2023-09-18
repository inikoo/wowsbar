<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\Hydrators;

use App\Models\Portfolio\Banner;
use Lorisleiva\Actions\Concerns\AsAction;

class BannerHydrateUniversalSearch
{
    use AsAction;

    public function handle(Banner $banner): void
    {
        $banner->universalSearch()->create(
            [
                'shop_id'     => $banner->customer->shop_id,
                'website_id'  => $banner->customer->website_id,
                'customer_id' => $banner->customer_id,
                'section'     => 'portfolio',
                'title'       => trim($banner->code.' '.$banner->name),
                'description' => ''
            ]
        );
    }

}
