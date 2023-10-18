<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 20:41:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\SysAdmin\ShowSysAdminDashboard;
use App\Enums\UI\Customer\CustomerUsersTabsEnum;
use App\Http\Resources\Auth\CustomerUserRequestLogsResource;
use App\Http\Resources\Auth\CustomerUserResource;
use App\Http\Resources\History\HistoryResource;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\CustomerUser;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCustomerUsers extends InertiaAction
{
    protected function getElementGroups(): array
    {
        return
            [
                'status' => [
                    'label'    => __('Status'),
                    'elements' => [
                        'active'    =>
                            [
                                __('Active'),
                                customer()->stats->number_users_status_active
                            ],
                        'suspended' => [
                            __('Suspended'),
                            customer()->stats->number_users_status_inactive
                        ]
                    ],
                    'engine'   => function ($query, $elements) {
                        $query->where('status', array_pop($elements) === 'active');
                    }

                ]
            ];
    }


    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('contact_name', $value)
                    ->orWhere('users.email', 'ILIKE', "$value%");
            });
        });


        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(CustomerUser::class);
        $queryBuilder->where('customer_id', customer()->id);
        foreach ($this->getElementGroups() as $key => $elementGroup) {
            /** @noinspection PhpUndefinedMethodInspection */
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('customer_user.slug')
            ->allowedSorts(['slug', 'email', 'contact_name'])
            ->allowedFilters([$globalSearch, 'email', 'contact_name', 'slug'])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            foreach ($this->getElementGroups() as $key => $elementGroup) {
                $table->elementGroup(
                    key: $key,
                    label: $elementGroup['label'],
                    elements: $elementGroup['elements']
                );
            }

            $table
                ->withGlobalSearch()
                ->withModelOperations($modelOperations)
                ->withExportLinks($exportLinks)
                ->column(key: 'avatar', label: ['fal', 'fa-user-circle'], type: 'avatar')
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'status', label: __('status'), canBeHidden: false, sortable: true)
                ->column(key: 'roles', label: __('roles'), canBeHidden: false, sortable: true)
                ->defaultSort('slug');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('sysadmin.edit');

        return
            (
                $request->get('customerUser')->hasPermissionTo('sysadmin.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $users): AnonymousResourceCollection
    {
        return CustomerUserResource::collection($users);
    }


    public function htmlResponse(LengthAwarePaginator $users, ActionRequest $request): Response
    {
        $customer=$request->get('customer');

        return Inertia::render(
            'SysAdmin/CustomerUsers',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                ),
                'title'       => __('users'),
                'pageHead'    => [
                    'title'     => __('users'),
                    'iconRight' => [
                        'title' => __('users'),
                        'icon'  => 'fal fa-user'
                    ],
                    'actions'   => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('create user'),
                            'route' => [
                                'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : []
                    ]
                ],


                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => CustomerUsersTabsEnum::navigation(),
                ],

                CustomerUsersTabsEnum::USERS->value => $this->tab == CustomerUsersTabsEnum::USERS->value ?
                    fn () => CustomerUserResource::collection($users)
                    : Inertia::lazy(fn () => CustomerUserResource::collection($users)),

                CustomerUsersTabsEnum::USERS_REQUESTS->value => $this->tab == CustomerUsersTabsEnum::USERS_REQUESTS->value ?
                    fn () => CustomerUserRequestLogsResource::collection(IndexCustomerUserRequestLogs::run($customer))
                    : Inertia::lazy(fn () => CustomerUserRequestLogsResource::collection(IndexCustomerUserRequestLogs::run($customer))),

                CustomerUsersTabsEnum::USERS_HISTORIES->value => $this->tab == CustomerUsersTabsEnum::USERS_HISTORIES->value ?
                    fn () => HistoryResource::collection(IndexCustomerHistory::run($customer, CustomerUser::class, 'history'))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run($customer, CustomerUser::class, 'history')))

            ]
        )->table(
            $this->tableStructure(
                prefix: 'users',
                exportLinks: [
                    'export' => [
                        'route' => [
                            'name' => 'export.users.index'
                        ]
                    ]
                ]
            )
        )->table(IndexCustomerUserRequestLogs::make()->tableStructure(parent:$customer))
        ->table(IndexCustomerHistory::make()->tableStructure('history'));
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(CustomerUsersTabsEnum::values());

        return $this->handle(prefix: 'users');
    }

    public function getBreadcrumbs(string $routeName): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('users'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'customer.sysadmin.users.index' =>
            array_merge(
                ShowSysAdminDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'customer.sysadmin.users.index',
                        null
                    ]
                ),
            ),


            default => []
        };
    }

}
