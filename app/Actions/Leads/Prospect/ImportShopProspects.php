<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\Helpers\Uploads\ConvertUploadedFile;
use App\Actions\Helpers\Uploads\Hydrators\UploadHydrateExcels;
use App\Actions\Helpers\Uploads\ImportModel;
use App\Actions\Helpers\Uploads\StoreExcelUploads;
use App\Imports\Catalogue\ProductImport;
use App\Imports\CRM\CustomerImport;
use App\Imports\Leads\ProspectImport;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Excel;
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class ImportShopProspects
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction          = false;

    public function handle(Shop $shop, $file): void
    {
        $prospectUpload = StoreExcelUploads::run($file, Prospect::class);
        $excelUpload = ImportModel::run(new ProspectImport($shop, $prospectUpload), $prospectUpload);

        UploadHydrateExcels::dispatch($excelUpload);
    }

    /**
     * @throws \Throwable
     */
    public function asController(Shop $shop, ActionRequest $request): void
    {
        $file = $request->file('file');
        $this->handle($shop, $file);
    }

    public string $commandSignature = 'shop:import-prospects {shop} {filename}';

    public function asCommand(Command $command): int
    {

        try {
            $shop = Shop::where('slug', $command->argument('shop'))->firstOrFail();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }


        $filename = $command->argument('filename');
        $file     = ConvertUploadedFile::run($filename);

        $this->handle($shop, $file);

        $command->line('Prospect imported');

        return 0;
    }
}
