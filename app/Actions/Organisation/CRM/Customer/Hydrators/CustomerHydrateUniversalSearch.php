<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Customer\Hydrators;

use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydrateUniversalSearch
{
    use AsAction;

    public function handle(Customer $customer): void
    {
        $customer->universalSearch()->updateOrCreate(
            [],
            [
                'section' => 'crm',
                'title'   => trim($customer->email.' '.$customer->contact_name.' '.$customer->company_name),
            ]
        );
    }

}
