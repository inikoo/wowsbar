<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\Hydrators;

use App\Models\Auth\User;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class WebsiteHydrateUniversalSearch
{
    use AsAction;

    public function handle(Website $website): void
    {
        $website->universalSearch()->create(
            [
                'section' => 'Website',
                'route'   => json_encode([
                    'name'      => 'web.websites.show',
                    'arguments' => [
                        $website->slug
                    ]
                ]),
                'icon'           => 'fa-globe',
                'primary_term'   => $website->name,
                'secondary_term' => $website->domain
            ]
        );
    }

}
