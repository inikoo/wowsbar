<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Slide;

use App\Actions\Auth\User\UI\AttachImageToTenant;
use App\Models\Portfolio\Slide;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachImageToSlide
{
    use AsAction;


    public function handle(Slide $contentBlockComponent, array $imageData): void
    {
        $imagePath       =null;
        $originalFilename=null;
        switch (Arr::get($imageData, 'source')) {
            case 'resources':
                $imagePath       =resource_path('art/banner/'.Arr::get($imageData, 'imageSrc'));
                $originalFilename=Arr::get($imageData, 'imageSrc');
        }

        if($imagePath) {
            $media = AttachImageToTenant::run(
                tenant: app('currentTenant'),
                collection: 'content_block',
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
