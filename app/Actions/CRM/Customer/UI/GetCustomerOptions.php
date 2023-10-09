<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Actions\InertiaAction;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsObject;

class GetCustomerOptions extends InertiaAction
{
    use AsObject;

    public function handle($customers): array
    {
        $selectOptions = [];
        /** @var Customer $customer */
        foreach ($customers as $customer) {
            $selectOptions[$customer->id] =
                [
                    'code' => $customer->slug,
                    'id'   => $customer->id,
                    'name' => $customer->name,
                ];
        }

        return $selectOptions;
    }
}
