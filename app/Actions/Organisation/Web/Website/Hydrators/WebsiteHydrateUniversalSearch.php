<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 17:43:36 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website\Hydrators;

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
                'section'     => 'websites',
                'title'       => trim($website->code.' '.$website->name.' '.$website->domain),
                'description' => ''
            ]
        );
    }

}
