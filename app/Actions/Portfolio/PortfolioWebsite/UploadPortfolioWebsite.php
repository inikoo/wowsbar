<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Actions\Portfolio\Uploads\StorePortfolioWebsiteUploads;
use App\Imports\WebsiteImport;
use App\Models\Portfolio\PortfolioWebsite;
use Excel;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UploadPortfolioWebsite
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction = false;

    public function handle(ActionRequest $request): void
    {
        $file     = $request->file('file');
        $filename = $file->hashName();

        $path = 'tenants/' . customer()->slug . '/websites';
        Storage::disk('local')->put($path, $file);

        $websiteUpload = StorePortfolioWebsiteUploads::run(customer(), [
            'type'              => class_basename(PortfolioWebsite::class),
            'original_filename' => $file->getClientOriginalName(),
            'filename'          => $filename
        ]);

        Excel::import(new WebsiteImport($websiteUpload), $path . '/' . $filename);
    }

    /**
     * @throws \Throwable
     */
    public function asController(ActionRequest $request): void
    {
        $this->handle($request);
    }
}
