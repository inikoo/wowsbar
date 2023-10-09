<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 09 Oct 2023 13:59:22 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Webpage;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Webpage;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttachImageToWebpage
{
    use AsAction;
    use WithActionUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Webpage $webpage, string $collection, string $imagePath, string $originalFilename, string $extension = null): Media
    {
        $checksum = md5_file($imagePath);
        /** @var Media $media */
        $media = $webpage->media()->where('collection_name', $collection)->where('checksum', $checksum)->first();

        if ($media) {
            return $media;
        }

        $filename = dechex(crc32($checksum)).'.';
        $filename .= empty($extension) ? pathinfo($imagePath, PATHINFO_EXTENSION) : $extension;

        $media= $webpage->addMedia($imagePath)
            ->preservingOriginal()
            ->withProperties(
                [
                    'checksum' => $checksum,
                ]
            )
            ->usingName($originalFilename)
            ->usingFileName($filename)
            ->toMediaCollection($collection);
        $media->refresh();
        return $media;
    }
}
