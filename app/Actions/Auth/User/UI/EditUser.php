<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User\UI;

use App\Actions\Auth\CustomerUser\UI\ShowCustomerUser;
use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithUserFields;
use App\Models\Auth\User;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditUser extends InertiaAction
{
    use WithUserFields;

    public function handle(User $user): User
    {
        return $user;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("sysadmin.view");
    }

    public function asController(User $user, ActionRequest $request): User
    {
        $this->initialisation($request);

        return $this->handle($user);
    }



    public function htmlResponse(User $user, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                'title'       => __('user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'pageHead'    => [
                    'title'     => $user->username,
                    'actions'   => [
                      [
                          'type'  => 'button',
                          'style' => 'exit',
                          'route' => [
                              'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                              'parameters' => array_values($request->route()->originalParameters())
                          ]
                      ]
                    ],
                ],

                'formData' => [
                    'blueprint' => $this->getUserFields($user),
                    'args'      => [
                        'updateRoute' => [
                            'name'      => 'models.user.update',
                            'parameters'=> [$user->username]

                        ],
                    ]
                ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowCustomerUser::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }
}
