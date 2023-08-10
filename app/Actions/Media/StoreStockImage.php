<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Media;

use App\Actions\Auth\User\UI\AttachImageToTenant;
use App\Models\Media\LandlordMedia;
use App\Models\Portfolio\ContentBlockComponent;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class StoreStockImage
{
    use AsAction;


    public function handle( string $imagePath): void
    {

        $landlord=LandLord::find(1);
        $checksum = md5_file($imagePath);
        $media = LandlordMedia::where('checksum', $checksum)->first();
        if (!$media) {
            /*
            $media = $tenant->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(['checksum' => $checksum])
                ->usingName($originalFilename)
                ->usingFileName($checksum.".".$extension ?? pathinfo($imagePath, PATHINFO_EXTENSION))
                ->toMediaCollection($collection);
            */
        }

       // return $media;

    }


}
