<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 22:31:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Surveys;

use App\Enums\EnumHelperTrait;

enum SurveyTypeEnum: string
{
    use EnumHelperTrait;

    case OPEN          = 'open';
    case OPTION        = 'option';
}
