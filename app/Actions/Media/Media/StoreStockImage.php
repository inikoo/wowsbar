<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 07 Feb 2024 11:29:10 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Media\Media;

use App\Models\Media\Media;
use App\Models\SysAdmin\Organisation;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreStockImage
{
    use AsAction;


    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     */
    public function handle(
        string $collection,
        string $scope,
        string $imagePath,
        string $originalFilename,
        string $extension=null
    ): \Spatie\MediaLibrary\MediaCollections\Models\Media {

        $organisation =Organisation::find(1);
        $checksum     = md5_file($imagePath);

        $organisationMedia = Media::where('checksum', $checksum)->first();
        if (!$organisationMedia) {
            $filename=dechex(crc32($checksum)).'.';

            if (empty($extension)) {
                $extension=pathinfo($imagePath, PATHINFO_EXTENSION);
            }

            $filename.=$extension;

            $name =preg_replace('/\..*$/', '', $originalFilename);
            $name =preg_replace('/_/', ' ', $name);
            $media= $organisation->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(
                    [
                        'scope'      => $scope,
                        'checksum'   => $checksum,
                    ]
                )
                ->usingName($name)
                ->usingFileName($filename)
                ->toMediaCollection($collection);
            $media->refresh();
            UpdateIsAnimatedMedia::run($media, $imagePath);
            return $media;

        } else {
            return $organisationMedia;

        }


    }


}
