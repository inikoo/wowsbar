<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 13:51:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Guest\UI;

use App\Actions\InertiaAction;
use App\Models\Auth\Guest;
use App\Models\HumanResources\JobPosition;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class EditGuest extends InertiaAction
{
    public function handle(Guest $guest): Guest
    {
        return $guest;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('sysadmin.edit');

        return $request->user()->hasPermissionTo("sysadmin.view");
    }

    public function asController(Guest $guest, ActionRequest $request): Guest
    {
        $this->initialisation($request);

        return $this->handle($guest);
    }


    public function htmlResponse(Guest $guest, ActionRequest $request): Response
    {
        $sections['properties'] = [
            'label'  => __('personal information'),
            'icon'   => 'fal fa-id-card',
            'fields' => [
                'contact_name' => [
                    'type'  => 'input',
                    'label' => __('name'),
                    'value' => $guest->contact_name
                ],
                'email'        => [
                    'type'  => 'input',
                    'label' => __('email'),
                    'value' => $guest->email
                ],
                'phone'        => [
                    'type'  => 'phone',
                    'label' => __('phone'),
                    'value' => $guest->phone
                ],
            ]
        ];

        $sections['job_position'] = [
            'label'  => __('Job position'),
            'icon'   => 'fal fa-handshake',
            'fields' => [
                'positions' => [
                    'type'        => 'jobPosition',
                    'label'       => __('position'),
                    'options'     => Options::forModels(JobPosition::class, label: 'name', value: 'name'),
                    'placeholder' => __('Select a job position'),
                    'value'       => $guest->jobPositions,
                ],
            ]
        ];

        $sections['delete'] = [
            'label'  => __('Delete'),
            'icon'   => 'fal fa-trash-alt',
            'fields' => [
                'name' => [
                    'type'   => 'action',
                    'action' => [
                        'type'  => 'button',
                        'style' => 'delete',
                        'label' => __('delete guest'),
                        'method'=> 'delete',
                        'route' => [
                            'name'       => 'org.models.guests.delete',
                            'parameters' => [
                                'guest' => $guest->id
                            ]
                        ]
                    ],
                ]
            ]
        ];

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'title'       => __('guest'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'pageHead'    => [
                    'title'   => $guest->contact_name,
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ],
                ],

                'formData' => [
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.guests.update',
                            'parameters' => $guest->id
                        ],
                    ]
                ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowGuest::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }
}
