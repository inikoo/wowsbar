<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 10:41:46 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Tenant;

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
                'icon'    => ['fal', 'fa-briefcase'],
                'label'   => __('Portfolio'),
                'route'   => 'portfolio.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-briefcase'],
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
