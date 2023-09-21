<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\Hydrators;

use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class WebsiteHydrateUniversalSearch
{
    use AsAction;


    public function handle(Website $website): void
    {
        $website->universalSearch()->updateOrCreate(
            [],
            [
                'shop_id'     => $website->shop_id,
                'website_id'  => $website->id,
                'section'     => 'websites',
                'title'       => trim($website->code.' '.$website->name.' '.$website->domain),
                'description' => ''
            ]
        );
    }

}
