<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 20:44:32 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\InertiaAction;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Actions\Traits\WithElasticsearch;
use App\Actions\UI\Customer\SysAdmin\ShowSysAdminDashboard;
use App\Enums\Auth\CustomerUser\CustomerUserTabsEnum;
use App\Http\Resources\Auth\CustomerUserRequestLogsResource;
use App\Http\Resources\Auth\CustomerUserResource;
use App\Http\Resources\History\CustomerHistoryResource;
use App\Models\Auth\CustomerUser;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowCustomerUser extends InertiaAction
{
    use WithElasticsearch;
    use WithActionButtons;


    public function asController(CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $this->initialisation($request)->withTab(CustomerUserTabsEnum::values());

        return $customerUser;
    }

    public function jsonResponse(CustomerUser $customerUser): CustomerUserResource
    {
        return new CustomerUserResource($customerUser);
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('sysadmin.edit');

        return $request->get('customerUser')->hasPermissionTo("sysadmin.view");
    }

    public function htmlResponse(CustomerUser $customerUser, ActionRequest $request): Response
    {
        $customer = $request->get('customer');

        return Inertia::render(
            'SysAdmin/CustomerUser',
            [
                'title'       => __('user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($customerUser, $request),
                    'next'     => $this->getNext($customerUser, $request),
                ],
                'pageHead'    => [
                    'icon'         => [
                        'icon'    => 'fal fa-terminal',
                        'tooltip' => __('User')
                    ],
                    'title'        => $customerUser->user->email,
                    'noCapitalise' => true,
                    'actions'  => [
                        //  $this->canDelete ? $this->getDeleteActionIcon($request) : null,
                        $this->canEdit ? $this->getEditActionIcon($request) : null,
                    ],
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => CustomerUserTabsEnum::navigation()
                ],

                CustomerUserTabsEnum::SHOWCASE->value => $this->tab == CustomerUserTabsEnum::SHOWCASE->value ?
                    fn () => new CustomerUserResource($customerUser)
                    : Inertia::lazy(fn () => new CustomerUserResource($customerUser)),


                CustomerUserTabsEnum::REQUEST_LOGS->value => $this->tab == CustomerUserTabsEnum::REQUEST_LOGS->value ?
                    fn () => CustomerUserRequestLogsResource::collection(IndexCustomerUserRequestLogs::run($customerUser))
                    : Inertia::lazy(fn () => CustomerUserRequestLogsResource::collection(IndexCustomerUserRequestLogs::run($customerUser))),

                CustomerUserTabsEnum::HISTORY->value => $this->tab == CustomerUserTabsEnum::HISTORY->value ?
                    fn () => CustomerHistoryResource::collection(IndexCustomerHistory::run($customer, $customerUser, 'history'))
                    : Inertia::lazy(fn () => CustomerHistoryResource::collection(IndexCustomerHistory::run($customer, $customerUser, 'history')))

            ]
        )
            ->table(IndexCustomerUserRequestLogs::make()->tableStructure(parent: $customerUser))
            ->table(IndexCustomerHistory::make()->tableStructure(prefix: 'history'));
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
            'customer.sysadmin.users.show',
            'customer.sysadmin.users.edit' =>

            array_merge(
                ShowSysAdminDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    CustomerUser::where('slug', $routeParameters['customerUser'])->first(),
                    [
                        'index' => [
                            'name'       => 'customer.sysadmin.users.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.sysadmin.users.show',
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
        $previous = CustomerUser::where('slug', '<', $customerUser->slug)->where('customer_id', customer()->id)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(CustomerUser $customerUser, ActionRequest $request): ?array
    {
        $next = CustomerUser::where('slug', '>', $customerUser->slug)->where('customer_id', customer()->id)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?CustomerUser $customerUser, string $routeName): ?array
    {
        if (!$customerUser) {
            return null;
        }

        return match ($routeName) {
            'customer.sysadmin.users.show' => [
                'label' => $customerUser->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'customerUser' => $customerUser->slug
                    ]

                ]
            ]
        };
    }
}
