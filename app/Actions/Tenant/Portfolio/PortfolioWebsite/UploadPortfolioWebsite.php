<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite;

use App\Actions\Tenant\Portfolio\Uploads\StorePortfolioWebsiteUploads;
use App\Imports\WebsiteImport;
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

        $path = 'tenants/' . app('currentTenant')->slug . '/websites';
        Storage::disk('local')->put($path, $file);

        $websiteUpload = StorePortfolioWebsiteUploads::run(app('currentTenant'), [
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
