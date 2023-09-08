<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadPortfolioWebsiteUploadsTemplate
{
    use AsAction;
    use WithAttributes;

    public function handle(): StreamedResponse
    {
        return Storage::disk('local')->download('websites/template.xlsx');
    }

    public function asController(): StreamedResponse
    {
        return $this->handle();
    }
}
