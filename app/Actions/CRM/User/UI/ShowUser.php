<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User\UI;

use App\Actions\Auth\UserRequest\ShowUserRequestLogs;
use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\Helpers\History\IndexHistories;
use App\Actions\Helpers\History\ShowHistories;
use App\Actions\InertiaAction;
use App\Actions\Traits\WithElasticsearch;
use App\Enums\UI\UserTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\SysAdmin\UserRequestLogsResource;
use App\Http\Resources\SysAdmin\UserResource;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowUser extends InertiaAction
{
    use WithElasticsearch;


    private Customer|Organisation $parent;

    public function asController(User $user, ActionRequest $request): User
    {
        $this->initialisation($request)->withTab(UserTabsEnum::values());
        $this->parent = organisation();

        return $user;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomer(Customer $customer, User $user, ActionRequest $request): User
    {
        $this->initialisation($request)->withTab(UserTabsEnum::values());
        $this->parent = $customer;

        return $user;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, User $user, ActionRequest $request): User
    {
        $this->initialisation($request)->withTab(UserTabsEnum::values());
        $this->parent = $customer;

        return $user;
    }

    public function jsonResponse(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return $request->user()->hasPermissionTo("crm.view");
    }

    public function htmlResponse(User $user, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/User',
            [
                'title'       => __('user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($user, $request),
                    'next'     => $this->getNext($user, $request),
                ],
                'pageHead'    => [
                    'title'   => $user->username,
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : [],
                    ]
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => UserTabsEnum::navigation()
                ],

                UserTabsEnum::SHOWCASE->value => $this->tab == UserTabsEnum::SHOWCASE->value ?
                    fn () => new UserResource($user)
                    : Inertia::lazy(fn () => new UserResource($user)),

                UserTabsEnum::REQUEST_LOGS->value => $this->tab == UserTabsEnum::REQUEST_LOGS->value ?
                    fn () => UserRequestLogsResource::collection(ShowUserRequestLogs::run($user->username))
                    : Inertia::lazy(fn () => UserRequestLogsResource::collection(ShowUserRequestLogs::run($user->username))),

                UserTabsEnum::HISTORY->value => $this->tab == UserTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(ShowHistories::run($user))
                    : Inertia::lazy(fn () => HistoryResource::collection(ShowHistories::run($user)))

            ]
        )->table(ShowUserRequestLogs::make()->tableStructure())
            ->table(IndexHistories::make()->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (User $user, array $routeParameters, string $suffix) {
            return [
                [

                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('users')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $user->username,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };

        return match ($routeName) {
            'org.crm.customers.show.web-users.show',
            'org.crm.customers.show.web-users.edit' =>

            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.customers.show', $routeParameters),
                $headCrumb(
                    User::where('username', $routeParameters['user'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.customers.show.web-users.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.customers.show.web-users.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            'org.crm.shop.customers.show.web-users.show',
            'org.crm.shop.customers.show.web-users.edit' =>

            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.shop.customers.show', $routeParameters),
                $headCrumb(
                    User::where('username', $routeParameters['user'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.shop.customers.show.web-users.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.shop.customers.show.web-users.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(User $user, ActionRequest $request): ?array
    {
        $query = User::where('username', '<', $user->username);
        if (class_basename($this->parent) == 'Customer') {
            $query->where('customer_id', $this->parent->id);
        }
        $previous = $query->orderBy('username', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(User $user, ActionRequest $request): ?array
    {
        $query = User::where('username', '>', $user->username);
        if (class_basename($this->parent) == 'Customer') {
            $query->where('customer_id', $this->parent->id);
        }
        $next = $query->orderBy('username')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?User $user, string $routeName): ?array
    {
        if (!$user) {
            return null;
        }

        return match ($routeName) {
            'org.crm.customers.show.web-users.show' => [
                'label' => $user->username,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [$user->customer->slug, $user->username]
                ]
            ],
            'org.crm.shop.customers.show.web-users.show' => [
                'label' => $user->username,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [$user->customer->shop->slug, $user->customer->slug, $user->username]
                ]
            ]
        };
    }
}
