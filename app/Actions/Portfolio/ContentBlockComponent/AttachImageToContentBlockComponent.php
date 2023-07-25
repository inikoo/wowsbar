<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlockComponent;

use App\Actions\Auth\User\UI\AttachImageToTenant;
use App\Models\Portfolio\ContentBlockComponent;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachImageToContentBlockComponent
{
    use AsAction;


    public function handle(ContentBlockComponent $contentBlockComponent, array $imageData): void
    {
        $imagePath=null;
        $originalFilename=null;
        switch (Arr::get($imageData, 'source')) {
            case 'resources':
                $imagePath=resource_path('art/banner/'.Arr::get($imageData, 'imageSrc'));
                $originalFilename=Arr::get($imageData, 'imageSrc');
        }

        if($imagePath) {
            $media = AttachImageToTenant::run(
                tenant: app('currentTenant'),
                collection: 'contentBlock',
                imagePath: $imagePath,
                originalFilename: $originalFilename,
            );
            $contentBlockComponent->update(
                [
                    'image_id' => $media->id
                ]
            );
        }

    }


}
