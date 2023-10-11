<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\Media\StoreStockImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StockImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (glob(resource_path('art/stock_images/*/*/*')) as $filename) {
            $_filename = Str::after($filename, resource_path('art/stock_images'));
            if (preg_match('/\/(.*)\/(.*)/', $_filename, $fileData)) {
                $scope     = Str::before($fileData[1], '/');
                $imageName = $fileData[2];
                StoreStockImage::run(
                    collection: 'stock_images',
                    scope: $scope,
                    imagePath: resource_path('art/stock_images'.$_filename),
                    originalFilename: $imageName
                );
            }
        }
    }
}
