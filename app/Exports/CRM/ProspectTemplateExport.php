<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\CRM;

use Faker\Factory;
use Maatwebsite\Excel\Concerns\FromArray;

class ProspectTemplateExport extends Factory implements FromArray
{
    public function array(): array
    {
        return [
            ['Code', 'Contact Name', 'Company Name', 'Email', 'Phone', 'Contact Website', 'Tags']
        ];
    }
}
