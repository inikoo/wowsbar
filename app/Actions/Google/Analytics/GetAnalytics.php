<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Google\Analytics;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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

    public string $commandSignature = 'analytics:fetch {property} {days}';

    public function handle($propertyId, $periodDay): Collection
    {
        Analytics::setPropertyId($propertyId);

        return Analytics::fetchVisitorsAndPageViews(Period::days($periodDay));
    }

    public function asCommand(Command $command): int
    {
        $result =  $this->handle($command->argument('property'), $command->argument('days'));

        print_r($result);

        return 0;
    }
}
