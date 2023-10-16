<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 20:25:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\UI;

use App\Actions\InertiaAction;
use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditOrganisationUser extends InertiaAction
{
    public function handle(OrganisationUser $organisationUser): OrganisationUser
    {
        return $organisationUser;
    }

    public function htmlResponse(OrganisationUser $organisationUser, ActionRequest $request): Response
    {
        $sections['credentials'] = [
            'label'  => __('Credentials'),
            'icon'   => 'fal fa-key',
            'fields' => [
                'username' => [
                    'type'  => 'input',
                    'label' => __('username'),
                    'value' => $organisationUser->username
                ],
                'password' => [
                    'type'  => 'password',
                    'label' => __('password'),
                    'value' => ''
                ],
            ]
        ];

        $currentSection = 'credentials';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }


        return Inertia::render(
            'EditModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs($request->route()->getName()),
                'title'       => __('edit user'),
                'pageHead'    => [
                    'title'   => $organisationUser->username,
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.sysadmin.users.show',
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]
                ],
                'formData'    => [
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.organisation-user.update',
                            'parameters' => $organisationUser->id
                        ]
                    ],
                ],


            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('sysadmin.users.edit');
    }


    public function asController(OrganisationUser $organisationUser, ActionRequest $request): OrganisationUser
    {
        $this->initialisation($request);

        return $this->handle($organisationUser);
    }

    public function getBreadcrumbs(string $routeName): array
    {
        return array_merge(
            IndexOrganisationUsers::make()->getBreadcrumbs(
                routeName: preg_replace('/edit$/', 'index', $routeName),
            ),
            [
                [
                    'type'          => 'editingModel',
                    'creatingModel' => [
                        'label' => __('editing user'),
                    ]
                ]
            ]
        );
    }
}
