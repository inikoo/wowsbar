<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 12:51:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\UI;

use App\Enums\CRM\Prospect\ProspectContactedStateEnum;
use App\Enums\CRM\Prospect\ProspectFailStatusEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\CRM\Prospect\ProspectSuccessStatusEnum;
use App\Models\Market\Shop;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetProspectsDashboard
{
    use AsObject;

    public function handle(Shop $parent, ActionRequest $request): array
    {
        $routeParameters = $request->route()->originalParameters();


        $stats = [];


        $stats['prospects'] = [
            'label' => __('Prospects'),
            'count' => $parent->crmStats->number_prospects
        ];
        foreach (ProspectStateEnum::cases() as $case) {
            $stats['prospects']['cases'][] = [
                'value' => $case->value,
                'icon'  => ProspectStateEnum::stateIcon()[$case->value],
                'count' => ProspectStateEnum::count($parent)[$case->value],
                'label' => ProspectStateEnum::labels()[$case->value]
            ];
        }


        $stats['contacted'] = [
            'label' => __('Contacted'),
            'count' => $parent->crmStats->number_prospects_state_contacted
        ];
        foreach (ProspectContactedStateEnum::cases() as $case) {
            if($case==ProspectContactedStateEnum::NA) {
                continue;
            }
            $stats['contacted']['cases'][] = [
                'value' => $case->value,
                'icon'  => ProspectContactedStateEnum::stateIcon()[$case->value],
                'count' => ProspectContactedStateEnum::count($parent)[$case->value],
                'label' => ProspectContactedStateEnum::labels()[$case->value]
            ];
        }

        $stats['fail'] = [
            'label' => __('Failed'),
            'count' => $parent->crmStats->number_prospects_state_fail
        ];
        foreach (ProspectFailStatusEnum::cases() as $case) {
            if($case==ProspectFailStatusEnum::NA) {
                continue;
            }
            $stats['fail']['cases'][] = [
                'value' => $case->value,
                'icon'  => ProspectFailStatusEnum::statusIcon()[$case->value],
                'count' => ProspectFailStatusEnum::count($parent)[$case->value],
                'label' => ProspectFailStatusEnum::labels()[$case->value]
            ];
        }

        $stats['success'] = [
            'label' => __('Success'),
            'count' => $parent->crmStats->number_prospects_state_success
        ];
        foreach (ProspectSuccessStatusEnum::cases() as $case) {
            if($case==ProspectSuccessStatusEnum::NA) {
                continue;
            }
            $stats['success']['cases'][] = [
                'value' => $case->value,
                'icon'  => ProspectSuccessStatusEnum::statusIcon()[$case->value],
                'count' => ProspectSuccessStatusEnum::count($parent)[$case->value],
                'label' => ProspectSuccessStatusEnum::labels()[$case->value]
            ];
        }



        return [
            'prospectStats' => $stats,


            'crmStats' => $parent->crmStats,

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
