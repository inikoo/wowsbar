<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 07 Jun 2023 01:32:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Web\Webpage;

use App\Enums\EnumHelperTrait;

enum WebpagePurposeEnum: string
{
    use EnumHelperTrait;

    case STRUCTURAL = 'structural';
    case CONTENT    = 'content';
    case OPERATION  = 'operation';


    public static function labels(): array
    {
        return [
            'structural' => __('structural'),
            'content'    => __('content'),
            'operation'  => __('operation'),

        ];
    }
}
