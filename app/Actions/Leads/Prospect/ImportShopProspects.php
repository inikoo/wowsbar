<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Imports\Leads\ProspectImport;
use App\Models\Helpers\Upload;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;

class ImportShopProspects
{
    use WithImportModel;

    public function handle(Shop $scope, $file): Upload
    {
        $upload = StoreUploads::run($file, Prospect::class);

        return $this->init(
            $upload,
            new ProspectImport($scope, $upload)
        );

    }


    public function asController(Shop $shop, ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($shop, $file);
    }

    public string $commandSignature = 'shop:import-prospects {shop} {--g|g_drive} {filename}';

    public function rumImport($file, $command): Upload
    {

        $shop=Shop::where('slug', $command->argument('shop'))->firstOrFail();

        return $this->handle($shop, $file);

    }


}
