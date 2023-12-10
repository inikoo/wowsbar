<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Guest;

use App\Exports\Auth\GuestsTemplateExport;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadGuestsTemplate
{
    use AsAction;
    use WithAttributes;

    public function handle(): BinaryFileResponse
    {
        return Excel::download(new GuestsTemplateExport(), 'template.xlsx');
    }

    public function asController(): BinaryFileResponse
    {
        return $this->handle();
    }
}
