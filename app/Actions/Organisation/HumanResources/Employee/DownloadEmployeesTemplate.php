<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\HumanResources\Employee;

use App\Exports\PortfolioWebsite\WebsiteTemplateExport;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadEmployeesTemplate
{
    use AsAction;
    use WithAttributes;

    public function handle(): BinaryFileResponse
    {
        return Excel::download(new WebsiteTemplateExport(), 'template.xlsx');
    }

    public function asController(): BinaryFileResponse
    {
        return $this->handle();
    }
}
