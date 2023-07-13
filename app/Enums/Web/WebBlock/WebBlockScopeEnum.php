<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 05 Jul 2023 15:53:24 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Web\WebBlock;

use App\Enums\EnumHelperTrait;

enum WebBlockScopeEnum: string
{
    use EnumHelperTrait;


    case WEBSITE  = 'website';
    case WEBPAGE  = 'webpage';



}
