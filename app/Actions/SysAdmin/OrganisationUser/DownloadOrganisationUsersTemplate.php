<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Exports\Market\ProductTemplateExport;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadOrganisationUsersTemplate
{
    use AsAction;
    use WithAttributes;

    public function handle(): BinaryFileResponse
    {
        return Excel::download(new ProductTemplateExport(), 'template.xlsx');
    }

    public function asController(): BinaryFileResponse
    {
        return $this->handle();
    }
}
