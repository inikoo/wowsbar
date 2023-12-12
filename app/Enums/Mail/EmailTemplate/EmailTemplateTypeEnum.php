<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Dec 2023 03:24:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Mail\EmailTemplate;

use App\Enums\EnumHelperTrait;

enum EmailTemplateTypeEnum: string
{
    use EnumHelperTrait;


    case MARKETING  = 'marketing';
    case NEWSLETTER = 'newsletter';


    public function label(): string
    {
        return match ($this) {
            EmailTemplateTypeEnum::MARKETING  => 'Marketing',
            EmailTemplateTypeEnum::NEWSLETTER => 'Newsletter',
        };
    }


}
