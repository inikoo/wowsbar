<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 13:51:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Guest\UI;

use App\Actions\InertiaAction;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreateGuest extends InertiaAction
{
    /**
     * @throws \Exception
     */
    public function htmlResponse(ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new guest'),
                'pageHead'    => [
                    'title'        => __('new guest'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.sysadmin.guests.index',
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title' => __('Type/Login credentials'),

                            'fields' => [
                                'type'             => [
                                    'type'    => 'radio',
                                    'mode'    => 'normal',
                                    'label'   => __('type'),
                                    'value'   => GuestTypeEnum::CONTRACTOR->value,
                                    'options' => Options::forEnum(GuestTypeEnum::class)
                                ],
                                'guestCredentials' => [
                                    'type'    => 'guest-credentials',
                                    'apiUrl'  => '/', // route('json.group-users.index') . '?filter[contact_name]=',
                                    'label'   => 'Guest Credentials',
                                    'value'   => 'newGroupUser',
                                    'options' => [
                                        'newGroupUser' => [
                                            'label'             => __('Create new user'),
                                            'hooks'             => [
                                                'route' => [
                                                    'name' => 'org.models.guest.store',
                                                ],
                                                'field' => [
                                                    'username' => [
                                                        'type'     => 'input',
                                                        'label'    => __('username'),
                                                        'value'    => '',
                                                        'required' => true,
                                                    ],
                                                ]
                                            ],
                                            'existingGroupUser' => [
                                                'label' => __('Use existing user from other aiku account'),
                                                'hooks' => [
                                                    'route' => [
                                                        'name' => 'org.models.group-user.guest.store',
                                                    ],
                                                ],
                                                'field' => [
                                                    'group_user_id' => [
                                                        'type'     => 'async-combobox',
                                                        'label'    => __('user'),
                                                        'value'    => '',
                                                        'required' => true,
                                                    ],
                                                ]


                                            ]
                                        ]
                                    ],


                                ]
                            ],

                        ],
                        [
                            'title'  => __('personal information'),
                            'fields' => [
                                'company_name' => [
                                    'type'  => 'input',
                                    'label' => __('company'),
                                    'value' => '',
                                ],
                                'contact_name' => [
                                    'type'     => 'input',
                                    'label'    => __('name'),
                                    'value'    => '',
                                    'required' => true
                                ],
                                'phone'        => [
                                    'type'  => 'phone',
                                    'label' => __('phone'),
                                    'value' => ''
                                ],
                                'email'        => [
                                    'type'  => 'input',
                                    'label' => __('email'),
                                    'value' => ''
                                ],
                            ]
                        ],

                    ],
                    'route'     => [
                        'name' => 'org.models.guests.store',

                    ]

                ],


            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('sysadmin.users.edit');
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request);
        return $request;

    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexGuest::make()->getBreadcrumbs(),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating guest'),
                    ]
                ]
            ]
        );
    }
}
