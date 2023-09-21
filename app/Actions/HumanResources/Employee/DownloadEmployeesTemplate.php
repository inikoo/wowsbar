<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Exports\HumanResources\EmployeeTemplateExport;
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
        return Excel::download(new EmployeeTemplateExport(), 'template.xlsx');
    }

    public function asController(): BinaryFileResponse
    {
        return $this->handle();
    }
}
