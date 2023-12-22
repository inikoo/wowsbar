<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 12:53:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\Crawl;

use App\Enums\EnumHelperTrait;

enum CrawlTypeEnum: string
{
    use EnumHelperTrait;

    case AURORA           = 'aurora';
    case SPATIE_CRAWLER   = 'spatie-crawler';

}
