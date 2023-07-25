<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlockComponent;

use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\ContentBlockComponent;
use Arr;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreContentBlockComponent
{
    use AsAction;


    public function handle(ContentBlock $contentBlock, array $modelData): ContentBlockComponent
    {

        $imageData=Arr::pull($modelData,'imageData');

        data_fill($modelData,'ulid',Str::ulid());
        /** @var ContentBlockComponent $contentBlockComponent */
        $contentBlockComponent= $contentBlock->contentBlockComponents()->create($modelData);

        AttachImageToContentBlockComponent::run($contentBlockComponent,$imageData);
        return $contentBlockComponent;
    }


}
