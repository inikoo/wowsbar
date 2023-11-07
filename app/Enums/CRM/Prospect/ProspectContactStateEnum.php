<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 07 Nov 2023 14:08:39 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\CRM\Prospect;

use App\Enums\EnumHelperTrait;

enum ProspectContactStateEnum: string
{
    use EnumHelperTrait;

    case CONTACTED         = 'contacted';
    case NO_CONTACTED      = 'no-contacted';




}
