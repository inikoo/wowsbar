<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User\UI;

use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\InertiaAction;
use App\Actions\Traits\WithElasticsearch;
use App\Enums\UI\UserTabsEnum;
use App\Http\Resources\SysAdmin\UserResource;
use App\Models\Auth\CustomerUser;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowOrgCustomerUser extends InertiaAction
{
    use WithElasticsearch;


    private Customer|Organisation $parent;

    public function asController(CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $this->initialisation($request)->withTab(UserTabsEnum::values());
        $this->parent = organisation();

        return $customerUser;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomer(Customer $customer, CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $this->initialisation($request)->withTab(UserTabsEnum::values());
        $this->parent = $customer;

        return $customerUser;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $this->initialisation($request)->withTab(UserTabsEnum::values());
        $this->parent = $customer;

        return $customerUser;
    }


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return $request->user()->hasPermissionTo("crm.view");
    }

    public function htmlResponse(CustomerUser $customerUser, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/OrgCustomerUser',
            [
                'title'       => __('customerUser'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($customerUser, $request),
                    'next'     => $this->getNext($customerUser, $request),
                ],
                'pageHead'    => [
                    'title'   => $customerUser->slug,
                    'icon'    => [
                        'title' => __('user'),
                        'icon'  => 'fal fa-terminal'
                    ],
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
                    fn() => new UserResource($customerUser)
                    : Inertia::lazy(fn() => new UserResource($customerUser)),
                /*
                                UserTabsEnum::REQUEST_LOGS->value => $this->tab == UserTabsEnum::REQUEST_LOGS->value ?
                                    fn () => UserRequestLogsResource::collection(ShowUserRequestLogs::run($customerUser->slug))
                                    : Inertia::lazy(fn () => UserRequestLogsResource::collection(ShowUserRequestLogs::run($customerUser->slug))),
                */
                // UserTabsEnum::HISTORY->value => $this->tab == UserTabsEnum::HISTORY->value ?
                //     fn () => HistoryResource::collection(ShowHistories::run($customerUser))
                //     : Inertia::lazy(fn () => HistoryResource::collection(ShowHistories::run($customerUser)))

            ]
        );
        //->table(ShowUserRequestLogs::make()->tableStructure())
        //->table(IndexHistory::make()->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (CustomerUser $customerUser, array $routeParameters, string $suffix) {
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
                            'label' => $customerUser->slug,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };

        return match ($routeName) {
            'org.crm.customers.show.customer-users.show',
            'org.crm.customers.show.customer-users.edit' =>

            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.customers.show', $routeParameters),
                $headCrumb(
                    CustomerUser::where('slug', $routeParameters['customerUser'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.customers.show.customer-users.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.customers.show.customer-users.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            'org.crm.shop.customers.show.customer-users.show',
            'org.crm.shop.customers.show.customer-users.edit' =>

            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.shop.customers.show', $routeParameters),
                $headCrumb(
                    CustomerUser::where('slug', $routeParameters['customerUser'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.shop.customers.show.customer-users.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.shop.customers.show.customer-users.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(CustomerUser $customerUser, ActionRequest $request): ?array
    {
        $query = CustomerUser::where('slug', '<', $customerUser->slug);
        if (class_basename($this->parent) == 'Customer') {
            $query->where('customer_id', $this->parent->id);
        }
        $previous = $query->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request);
    }

    public function getNext(CustomerUser $customerUser, ActionRequest $request): ?array
    {
        $query = CustomerUser::where('slug', '>', $customerUser->slug);
        if (class_basename($this->parent) == 'Customer') {
            $query->where('customer_id', $this->parent->id);
        }
        $next = $query->orderBy('slug')->first();

        return $this->getNavigation($next, $request);
    }

    private function getNavigation(?CustomerUser $customerUser, ActionRequest $request): ?array
    {
        $routeName = $request->route()->getName();

        if (!$customerUser) {
            return null;
        }

        return match ($routeName) {
            'org.crm.customers.show.customer-users.show', 'org.crm.shop.customers.show.customer-users.show' => [
                'label' => $customerUser->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => array_merge($request->route()->originalParameters(), [$customerUser->slug])
                ]
            ]
        };
    }
}
