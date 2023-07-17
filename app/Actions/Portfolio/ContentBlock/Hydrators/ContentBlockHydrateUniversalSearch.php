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
                'section' => 'portfolio',
                'title'   => trim($contentBlock->code.' '.$contentBlock->name),
                'description' => ''
            ]
        );
    }

}
