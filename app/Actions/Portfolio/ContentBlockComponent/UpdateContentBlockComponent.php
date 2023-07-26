<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 26 Jul 2023 23:03:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlockComponent;


use App\Actions\Traits\WithActionUpdate;
use App\Models\Portfolio\ContentBlockComponent;
use Arr;

class UpdateContentBlockComponent
{
    use WithActionUpdate;


    public function handle(ContentBlockComponent $contentBlockComponent, array $modelData): ContentBlockComponent
    {
        $imageData=Arr::pull($modelData,'imageData');

        $this->update($contentBlockComponent, $modelData, ['layout']);

        if($imageData){
            AttachImageToContentBlockComponent::run($contentBlockComponent,$imageData);
        }
        return $contentBlockComponent;
    }


}
