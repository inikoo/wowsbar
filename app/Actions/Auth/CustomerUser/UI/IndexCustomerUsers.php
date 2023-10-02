<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 20:41:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\UI;

use App\Actions\Auth\UserRequest\IndexUserRequestLogs;
use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\SysAdmin\ShowSysAdminDashboard;
use App\Enums\UI\UsersTabsEnum;
use App\Http\Resources\Auth\CustomerUserResource;
use App\Http\Resources\Auth\UserRequestLogsResource;
use App\Http\Resources\History\HistoryResource;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\User;
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
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        return $queryBuilder
            ->defaultSort('customer_user.slug')
            ->allowedSorts(['slug', 'email', 'contact_name'])
            ->allowedFilters([$globalSearch,'email','contact_name','slug'])
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
                ->column(key: 'avatar', label: ['fal', 'fa-user-circle'])
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'status', label: __('status'), canBeHidden: false, sortable: true)
                ->column(key: 'roles', label: __('roles'), canBeHidden: false, sortable: true)
                ->column(key: 'permissions', label: __('permissions'), canBeHidden: false, sortable: true)
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
                        [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => 'create user',
                            'route' => [
                                'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ]
                ],




                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => UsersTabsEnum::navigation(),
                ],

                UsersTabsEnum::USERS->value => $this->tab == UsersTabsEnum::USERS->value ?
                    fn () => CustomerUserResource::collection($users)
                    : Inertia::lazy(fn () => CustomerUserResource::collection($users)),

                UsersTabsEnum::USERS_REQUESTS->value => $this->tab == UsersTabsEnum::USERS_REQUESTS->value ?
                    fn () => UserRequestLogsResource::collection(IndexUserRequestLogs::run($request->get('sort')))
                    : Inertia::lazy(fn () => UserRequestLogsResource::collection(IndexUserRequestLogs::run())),

                UsersTabsEnum::USERS_HISTORIES->value => $this->tab == UsersTabsEnum::USERS_HISTORIES->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(User::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(User::class)))

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
        )->table(IndexUserRequestLogs::make()->tableStructure())
            ->table(IndexHistories::make()->tableStructure(
                exportLinks: [
                'export' => [
                    'route' => [
                        'name' => 'export.histories.index'
                    ]
                ]
            ]
            ));
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(UsersTabsEnum::values());

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