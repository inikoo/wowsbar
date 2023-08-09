<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 07 Sept 2022 22:03:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\UI;

use App\Models\Auth\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLayout
{
    use AsAction;

    public function handle(User $user): array
    {
        $navigation = [];

        if ($user->can('portfolio.view')) {
            $navigation['portfolio'] = [
                'scope'   => 'portfolio',
                'icon'    => ['fal', 'fa-puzzle-piece'],
                'label'   => __('Web blocks'),
                'route'   => 'portfolio.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-chart-network'],
                            'route' => [
                                'name' => 'portfolio.dashboard',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'portfolio.websites.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-window-maximize'],
                            'label' => __('banners'),
                            'route' => [
                                'name' => 'portfolio.banners.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-photo-video'],
                            'label' => __('gallery'),
                            'route' => [
                                'name' => 'portfolio.gallery',
                            ]
                        ],

                    ],

                ]


            ];
        }



        if ($user->can('sysadmin')) {
            $navigation['sysadmin'] = [
                'label'   => __('sysadmin'),
                'icon'    => ['fal', 'fa-users-cog'],
                'route'   => 'sysadmin.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'label' => __('users'),
                            'icon'  => ['fal', 'fa-terminal'],
                            'route' => [
                                'name' => 'sysadmin.users.index',

                            ]
                        ],
                        [
                            'label' => __('system settings'),
                            'icon'  => ['fal', 'fa-cog'],
                            'route' => [
                                'name' => 'sysadmin.settings.edit',

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
