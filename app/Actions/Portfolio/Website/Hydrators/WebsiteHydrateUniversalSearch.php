<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Website\Hydrators;

use App\Models\Portfolio\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class WebsiteHydrateUniversalSearch
{
    use AsAction;

    public function handle(Website $website): void
    {
        $website->universalSearch()->create(
            [
                'section' => 'portfolio',
                'title'       => trim($website->code.' '.$website->name),
                'description' => $website->domain
            ]
        );
    }

}
