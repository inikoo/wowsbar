<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 22 Nov 2023 23:40:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetCustomersDashboard
{
    use AsObject;

    public function handle(Shop $parent, ActionRequest $request): array
    {
        $routeParameters = $request->route()->originalParameters();
        return [

            'crmStats'=> $parent->crmStats,

            'stats' => [

                [
                    'name' => __('customers'),
                    'stat' => $parent->crmStats->number_customers,
                    'href' => match (class_basename($parent)) {
                        'Shop' =>
                        [
                            'name'       => 'org.crm.shop.customers.index',
                            'parameters' =>
                                array_merge(
                                    $routeParameters,
                                    [
                                        '_query' => [
                                            'tab' => 'customers'
                                        ]
                                    ]
                                )
                        ],
                        default => [
                            'name' => 'org.crm.customers.index'
                        ]
                    }
                ]
            ]
        ];
    }

}
