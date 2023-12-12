<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Helpers\Uploads\ImportUpload;
use App\Actions\Helpers\Uploads\StoreUploads;
use App\Actions\Traits\WithImportModel;
use App\Http\Resources\Helpers\UploadsResource;
use App\Imports\Leads\ProspectImport;
use App\Models\Helpers\Upload;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;

class ImportShopProspects
{
    use WithImportModel;

    public function handle(Shop $scope, $file): Upload
    {
        $upload = StoreUploads::run($file, Prospect::class);

        if ($this->isSync) {
            ImportUpload::run(
                $file,
                new ProspectImport($scope, $upload)
            );
            $upload->refresh();
        } else {
            ImportUpload::dispatch(
                $this->tmpPath.$upload->filename,
                new ProspectImport($scope, $upload)
            );
        }


        return $upload;
    }


    public function inShop(Shop $shop, ActionRequest $request): Upload
    {
        $file = $request->file('file');
        Storage::disk('local')->put($this->tmpPath, $file);
        return $this->handle($shop, $file);
    }

    public function jsonResponse(Upload $upload): array
    {
        return UploadsResource::make($upload)->getArray();
    }

    public string $commandSignature = 'shop:import-prospects {shop} {--g|g_drive} {filename}';

    public function rumImport($file, $command): Upload
    {
        $shop = Shop::where('slug', $command->argument('shop'))->firstOrFail();

        return $this->handle($shop, $file);
    }


}
