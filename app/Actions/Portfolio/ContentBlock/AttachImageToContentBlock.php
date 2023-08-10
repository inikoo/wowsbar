<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 01 Aug 2023 12:43:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock;

use App\Actions\Auth\User\UI\AttachImageToTenant;
use App\Models\Media\Media;
use App\Models\Portfolio\ContentBlock;
use Illuminate\Http\UploadedFile;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachImageToContentBlock
{
    use AsAction;


    public function handle(ContentBlock $contentBlock, UploadedFile $file): Media
    {



        dd($file);
        /** @var Media $media */
        $media = AttachImageToTenant::run(
            tenant: app('currentTenant'),
            collection: 'contentBlock',
            imagePath: $file->getPathName(),
            originalFilename: $file->getClientOriginalName(),
            extension: $file->guessClientExtension()
        );

        return $media;
    }


}
