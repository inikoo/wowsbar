<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\HumanResources;

use Maatwebsite\Excel\Concerns\FromArray;

class EmployeeTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
              [
                  'Worker Number',
                  'Name',
                  'Alias',
                  'Job Title',
                  'Positions',
                  'Starting Date',
                  'Workplace',
                  'Username',
                  'password',
                  'reset password'
              ]
        ];
    }
}
