<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner;

use App\Actions\Tenant\Auth\User\UI\AttachImageToTenant;
use App\Models\Media\Media;
use App\Models\Portfolio\Banner;
use Illuminate\Http\UploadedFile;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachImageToBanner
{
    use AsAction;

    public function handle(Banner $banner, UploadedFile $file): Media
    {
        /** @var Media $media */
        $media = AttachImageToTenant::run(
            tenant: app('currentTenant'),
            collection: 'content_block',
            imagePath: $file->getPathName(),
            originalFilename: $file->getClientOriginalName(),
            extension: $file->guessClientExtension()
        );
        if(!$banner->image_id) {
            $banner->update(
                [
                    'data->unpublished_image_id',$media->id
                ]
            );
        }

        return $media;
    }


}
