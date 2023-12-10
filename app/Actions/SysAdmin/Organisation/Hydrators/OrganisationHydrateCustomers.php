<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 15:17:11 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateCustomers
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {
        $stats = [
            'number_customers' => Customer::count()
        ];

        array_merge($stats, $this->getEnumStats('customers', 'state', CustomerStateEnum::class, Customer::class));
        organisation()->crmStats()->update($stats);
    }
}
