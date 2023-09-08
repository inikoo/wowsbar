<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use App\Imports\WebsiteImport;
use App\Models\Tenancy\Tenant;
use App\Models\WebsiteUpload;
use Excel;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction = false;

    public function handle(Tenant $tenant, array $modelData): void
    {
        $tenant->portfolioWebsiteUploads()->create([
            'original_filename' => $modelData['original_filename'],
            'filename' => $modelData['filename'],
            'uploaded_at' => now()
        ]);
    }
}
