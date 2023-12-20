<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\Market;

use Maatwebsite\Excel\Concerns\FromArray;

class ProductTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Code', 'Unit', 'Price', 'Name', 'State', 'Type', 'Description']
        ];
    }
}
