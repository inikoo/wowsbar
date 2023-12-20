<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\PortfolioWebsite;

use Maatwebsite\Excel\Concerns\FromArray;

class WebsiteTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Code', 'Name', 'Domain']
        ];
    }
}
