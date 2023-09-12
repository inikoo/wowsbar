<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation;

use App\Models\Organisation\OrganisationUser;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLayout
{
    use AsAction;

    public function handle(OrganisationUser $user): array
    {
        $navigation = [];


        if ($user->can('website')) {
            $navigation['website'] = [
                'label'   => __('website'),
                'icon'    => ['fal', 'fa-globe'],
                'route'   => 'org.website.show',
                'topMenu' => [
                    'subSections' => [
                        [
                            'label' => __('website'),
                            'icon'  => ['fal', 'fa-globe'],
                            'route' => [
                                'name' => 'org.website.show',
                            ]
                        ],
                        [
                            'label' => __('webpages'),
                            'icon'  => ['fal', 'fa-browser'],
                            'route' => [
                                'name' => 'org.website.webpages.index',
                            ]
                        ],
                    ]
                ]
            ];
        }

        if ($user->can('crm.view')) {
            $navigation['crm'] = [
                'label' => __('Customers'),
                'icon'  => ['fal', 'fa-user'],

                'route'   => 'org.crm.dashboard',
                'topMenu' => [
                    'subSections' => [

                    ]
                ]

            ];
        }

        if (!$user->can('hr')) {
            $navigation['hr'] = [
                'label'   => __('human resources'),
                'icon'    => ['fal', 'fa-user-hard-hat'],
                'route'   => 'org.hr.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'label' => __('website'),
                            'icon'  => ['fal', 'fa-globe'],
                            'route' => [
                                'name' => 'org.website.show',
                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($user->can('sysadmin')) {
            $navigation['sysadmin'] = [
                'label'   => __('sysadmin'),
                'icon'    => ['fal', 'fa-users-cog'],
                'route'   => 'org.sysadmin.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'label' => __('users'),
                            'icon'  => ['fal', 'fa-terminal'],
                            'route' => [
                                'name' => 'org.sysadmin.users.index',

                            ]
                        ],
                        [
                            'label' => __('system settings'),
                            'icon'  => ['fal', 'fa-cog'],
                            'route' => [
                                'name' => 'org.sysadmin.organisation.edit',

                            ]
                        ],
                    ]
                ]
            ];
        }


        return [
            'navigation' => $navigation,
        ];
    }
}
