<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Organisation\Website\Webpage;

use App\Enums\EnumHelperTrait;

enum WebpagePurposeEnum: string
{
    use EnumHelperTrait;

    case STRUCTURAL          = 'structural';
    case CONTENT             = 'content';


    public static function labels(): array
    {
        return [
            'structural'              => 'structural',
            'content'                 => 'content',
        ];
    }
}
