<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithCustomerUserFields;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditUser extends InertiaAction
{
    use WithCustomerUserFields;

    public function handle(User $user): User
    {
        return $user;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.edit");
    }

    public function asController(User $user, ActionRequest $request): User
    {
        $this->initialisation($request);
        return $this->handle($user);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomer(Customer $customer, User $user, ActionRequest $request): User
    {
        $this->initialisation($request);
        return $this->handle($user);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, User $user, ActionRequest $request): User
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
                    $request->route()->originalParameters()
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
                    'blueprint' => $this->getCustomerUserFields($user),
                    'args'      => [
                        'updateRoute' => [
                            'name'      => 'models.user.update',
                            'parameters'=> [$user->id]

                        ],
                    ]
                ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowOrgCustomerUser::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }
}
