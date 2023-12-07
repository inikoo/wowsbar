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

class EmailTemplateImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (glob(resource_path('art/templates_mailshot/*')) as $filename) {
            $_filename = Str::after($filename, resource_path('art/templates_mailshot'));
            $scope = 'templates_mailshot';
            $imageName = $_filename;
            StoreStockImage::run(
                collection: 'templates_mailshot',
                scope: $scope,
                imagePath: resource_path('art/templates_mailshot' . $_filename),
                originalFilename: Str::replace('/', '', $imageName)
            );
        }
    }
}
