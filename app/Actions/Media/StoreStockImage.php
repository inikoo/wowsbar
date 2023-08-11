<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Media;

use App\Models\Landlord\Landlord;
use App\Models\Media\LandlordMedia;
use App\Models\Media\Media;
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
        string $imagePath,
        string $originalFilename,
        string $extension=null
    ): LandlordMedia|Media {

        $landlord =LandLord::find(1);
        $checksum = md5_file($imagePath);

        $landlordMedia = LandlordMedia::where('checksum', $checksum)->first();
        if (!$landlordMedia) {
            $filename=dechex(crc32($checksum)).'.';
            $filename.=empty($extension) ? pathinfo($imagePath, PATHINFO_EXTENSION) : $extension;


            $name=preg_replace('/\..*$/', '', $originalFilename);
            $name=preg_replace('/_/', ' ', $name);
            return $landlord->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(['checksum' => $checksum])
                ->usingName($name)
                ->usingFileName($filename)
                ->toMediaCollection($collection);

        } else {
            return $landlordMedia;

        }


    }


}
