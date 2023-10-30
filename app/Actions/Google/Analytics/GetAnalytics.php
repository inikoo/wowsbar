<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Google\Analytics;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsObject;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class GetAnalytics
{
    use AsObject;
    use AsAction;
    use AsCommand;

    public string $commandSignature = 'analytics:fetch {property} {startAt}';

    public function handle($propertyId, $startAt): Collection
    {
        Analytics::setPropertyId($propertyId);

        $analytics = Analytics::get(Period::create($startAt, now()), ['totalUsers', 'screenPageViews'], ['pagePath']);

        return $analytics->map(function ($analytic) {
            return [
                'bannerId'  => Str::replace('/banners/', '', $analytic['pagePath']),
                'pageViews' => $analytic['screenPageViews'],
                'users'     => $analytic['totalUsers']
            ];
        });
    }

    public function asCommand(Command $command): int
    {
        $result =  $this->handle($command->argument('property'), Carbon::make($command->argument('startAt')));

        print_r($result);

        return 0;
    }
}
