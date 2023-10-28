<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee;

use App\Exports\ExcelFailsExport;
use App\Models\Auth\OrganisationUser;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadEmployeeFailsExcel
{
    use AsAction;
    use WithAttributes;

    public function handle(OrganisationUser $organisationUser): BinaryFileResponse
    {
        /** @var \App\Models\Helpers\Upload $uploads */
        $uploads = $organisationUser->excelUploads()
            ->where('type', class_basename(Employee::class))->first();

        return Excel::download(new ExcelFailsExport($uploads), 'employees-fails-uploads-.xlsx');
    }

    public function asController(): BinaryFileResponse
    {
        $organisationUser = request()->user();

        return $this->handle($organisationUser);
    }
}
