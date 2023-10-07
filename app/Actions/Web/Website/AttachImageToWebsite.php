<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 13:47:01 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttachImageToWebsite
{
    use AsAction;
    use WithActionUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Website $website, string $collection, string $imagePath, string $originalFilename, string $extension = null): Media
    {
        $checksum = md5_file($imagePath);
        /** @var Media $media */
        $media = $website->media()->where('collection_name', $collection)->where('checksum', $checksum)->first();

        if ($media) {
            return $media;
        }



        $filename = dechex(crc32($checksum)).'.';
        $filename .= empty($extension) ? pathinfo($imagePath, PATHINFO_EXTENSION) : $extension;

        $media= $website->addMedia($imagePath)
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
