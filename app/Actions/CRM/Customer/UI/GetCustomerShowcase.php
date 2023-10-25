<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsObject;

class GetCustomerShowcase
{
    use AsObject;

    public function handle(Customer $customer): array
    {
        return [

                'blueprint' => [
                    'contact' => [
                        'title' => __('contact'),
                        'icon'  => ['fal', 'fa-user-circle']
                    ],
                    'sales'   => [
                        'title' => __('sales'),
                        'icon'  => ['fal', 'fa-usd-circle']
                    ],
                    'webUsers'   => [
                        'title' => __('webusers'),
                        'icon'  => ['fal', 'fa-terminal']
                    ]
                ],
                'current'=> 'contact'


        ];
    }
}
