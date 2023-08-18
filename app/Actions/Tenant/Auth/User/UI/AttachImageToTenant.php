<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\User\UI;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Tenancy\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttachImageToTenant
{
    use AsAction;
    use WithActionUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Tenant $tenant, string $collection, string $imagePath, string $originalFilename, string $extension = null): Media
    {
        $checksum = md5_file($imagePath);
        /** @var Media $media */
        $media = $tenant->media()->where('collection_name', $collection)->where('checksum', $checksum)->first();
        if (!$media) {
            $filename=dechex(crc32($checksum)).'.';
            $filename.=empty($extension) ? pathinfo($imagePath, PATHINFO_EXTENSION) : $extension;

            $media = $tenant->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(
                    [
                        'checksum'  => $checksum,
                        'tenant_id' => app('currentTenant')->id
                    ]
                )
                ->usingName($originalFilename)
                ->usingFileName($filename)
                ->toMediaCollection($collection);
        }
        return $media;
    }
}
