<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:40:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Exports\Auth;

use Maatwebsite\Excel\Concerns\FromArray;

class GuestsTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Type', 'Username', 'Name', 'Positions', 'Alias', 'Password', 'Reset Password']
        ];
    }
}
