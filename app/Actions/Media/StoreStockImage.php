<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Media;

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

            if(empty($extension)) {
                $extension=pathinfo($imagePath, PATHINFO_EXTENSION);
            }

            $filename.=$extension;

            $animated=false;
            if($extension and strtolower($extension)=='gif') {
                $animated=IsAnimatedGif::run($imagePath);
            }


            $name=preg_replace('/\..*$/', '', $originalFilename);
            $name=preg_replace('/_/', ' ', $name);
            return $organisation->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(
                    [
                        'scope'      => $scope,
                        'checksum'   => $checksum,
                        'is_animated'=> $animated
                    ]
                )
                ->usingName($name)
                ->usingFileName($filename)
                ->toMediaCollection($collection);

        } else {
            return $organisationMedia;

        }


    }


}
