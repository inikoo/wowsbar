<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\ContentBlockComponent;

use App\Enums\EnumHelperTrait;

enum ContentBlockComponentTypeEnum: string
{
    use EnumHelperTrait;


    case SLIDE  = 'slide';



}
