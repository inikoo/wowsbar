<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 14:49:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits\Fields;

trait WithCustomerUserFields
{
    protected function getCustomerUserFields($user): array
    {
        return  [
            [
                'title'    => __('id'),
                'icon'     => 'fal fa-user',
                'current'  => true,
                'fields'   => [

                    'username' => [
                        'type'  => 'input',
                        'label' => __('username'),
                        'value' => $user->username
                    ],
                    'email' => [
                        'type'  => 'input',
                        'label' => __('email'),
                        'value' => $user->email
                    ],
                    'contact_name' => [
                        'type'  => 'input',
                        'label' => __('name'),
                        'value' => $user->contact_name
                    ],

                ]
            ],
            [
                'title'    => __('Status'),
                'icon'     => 'fal fa-user-lock',
                'current'  => true,
                'fields'   => [
                    'status' => [
                        'type'      => 'toggleSquare',
                        'typeLabel' => ['suspended', 'active'],
                        'label'     => __('Status'),
                        'value'     => $user->status
                    ],
                ]
            ],
            'password'   => [
                'title'   => __('Password'),
                'icon'    => 'fal fa-key',
                'current' => false,
                'fields'  => [
                    'password' => [
                        'type'  => 'password',
                        'label' => __('password'),
                        'value' => ''
                    ],
                ]
            ],
            'permissions'   => [
                'title'   => __('Permissions'),
                'icon'    => 'fal fa-user-lock',
                'current' => false,
                'fields'  => [
                    'permissions' => [
                        'type'              => 'select',
                        'label'             => __('permissions'),
                        'options'           => $user->getRoleNames(),
                        'value'             => $user->getRoleNames(),
                        // 'fullComponentArea' => true,
                    ],
                ]
            ],

        ];
    }
}
