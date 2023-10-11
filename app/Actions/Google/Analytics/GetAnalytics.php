<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Google\Analytics;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsObject;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class GetAnalytics
{
    use AsObject;
    use AsAction;

    public function handle(): void
    {
        $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));

        dd($analyticsData);
    }
}
