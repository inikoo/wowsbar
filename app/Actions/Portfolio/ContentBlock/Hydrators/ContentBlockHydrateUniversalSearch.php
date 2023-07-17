<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Hydrators;

use App\Models\Portfolio\ContentBlock;
use Lorisleiva\Actions\Concerns\AsAction;

class ContentBlockHydrateUniversalSearch
{
    use AsAction;

    public function handle(ContentBlock $contentBlock): void
    {
        $contentBlock->universalSearch()->create(
            [
                'section' => 'Content Block',
                'route'   => json_encode([
                    'name'      => 'portfolio.websites.show',
                    'arguments' => [
                        $contentBlock->slug
                    ]
                ]),
                'icon'           => 'fa-globe',
                'primary_term'   => $contentBlock->name,
                'secondary_term' => $contentBlock->slug
            ]
        );
    }

}
