<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 10 Sep 2023 18:49:24 Malaysia Time, Plane KL-Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation;

use App\Models\SysAdmin\Organisation;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachImageToOrganisation
{
    use AsAction;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Organisation $organisation, string $collection, string $imagePath, ?string $originalFilename, string $extension = null): \Spatie\MediaLibrary\MediaCollections\Models\Media
    {

        $checksum = md5_file($imagePath);
        $filename =dechex(crc32($checksum)).'.';
        $filename.=empty($extension) ? pathinfo($imagePath, PATHINFO_EXTENSION) : $extension;



        $media= $organisation->addMedia($imagePath)
            ->preservingOriginal()
            ->withProperties(
                [
                    'checksum'  => $checksum,
                ]
            )
            ->usingName($originalFilename)
            ->usingFileName($filename)
            ->toMediaCollection($collection);

        $media->refresh();
        return $media;

    }


}
