<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 12:51:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\UI;

use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetProspectsDashboard
{
    use AsObject;

    public function handle(Shop $parent, ActionRequest $request): array
    {
        $routeParameters = $request->route()->originalParameters();
        return [

            'crmStats'=> $parent->crmStats,

            'stats' => [

                [
                    'name' => __('prospects'),
                    'stat' => $parent->crmStats->number_prospects,
                    'href' => match (class_basename($parent)) {
                        'Shop' =>
                        [
                            'name'       => 'org.crm.shop.prospects.index',
                            'parameters' =>
                                array_merge(
                                    $routeParameters,
                                    [
                                        '_query' => [
                                            'tab' => 'prospects'
                                        ]
                                    ]
                                )
                        ],
                        default => [
                            'name' => 'org.crm.prospects.index'
                        ]
                    }
                ]
            ]
        ];
    }

}
