<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Traits\WithActionUpdate;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttachImageToCustomer
{
    use AsAction;
    use WithActionUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Customer $customer, string $collection, string $imagePath, string $originalFilename, string $extension = null): Media
    {
        $checksum = md5_file($imagePath);
        /** @var Media $media */
        $media = $customer->media()->where('collection_name', $collection)->where('checksum', $checksum)->first();
        if (!$media) {
            $filename=dechex(crc32($checksum)).'.';
            $filename.=empty($extension) ? pathinfo($imagePath, PATHINFO_EXTENSION) : $extension;

            $media = $customer->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(
                    [
                        'checksum'    => $checksum,
                        'customer_id' => $customer->id
                    ]
                )
                ->usingName($originalFilename)
                ->usingFileName($filename)
                ->toMediaCollection($collection);
            $media->refresh();
        }
        return $media;
    }
}
