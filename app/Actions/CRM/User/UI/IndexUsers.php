<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 14:02:17 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User\UI;

use App\Actions\Auth\UserRequest\IndexUserRequestLogs;
use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Enums\UI\UsersTabsEnum;
use App\Http\Resources\Auth\OrganisationUserRequestLogsResource;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\History\HistoryResource;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexUsers extends InertiaAction
{
    /**
     * @var \App\Models\CRM\Customer|\App\Models\Organisation\Organisation
     */
    private Customer|Organisation $parent;

    protected function getElementGroups(Organisation|Customer $parent): array
    {
        return
            [
                'status' => [
                    'label'    => __('Status'),
                    'elements' => [
                        'active'    =>
                            [
                                __('Active'),
                                $parent->stats->number_users_status_active
                            ],
                        'suspended' => [
                            __('Suspended'),
                            $parent->stats->number_users_status_inactive
                        ]
                    ],
                    'engine'   => function ($query, $elements) {
                        $query->where('status', array_pop($elements) === 'active');
                    }

                ]
            ];
    }


    public function handle(Organisation|Customer $parent, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('contact_name', $value)
                    ->orWhere('users.username', 'ILIKE', "$value%");
            });
        });


        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(User::class);


        if(class_basename($parent)=='Customer') {
            $queryBuilder->where('customer_id', $parent->id);
        }

        foreach ($this->getElementGroups($parent) as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        return $queryBuilder
            ->defaultSort('username')
            ->allowedSorts(['username', 'email', 'contact_name'])
            ->allowedFilters([$globalSearch, 'email', 'contact_name', 'username'])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(Organisation|Customer $parent, ?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks, $parent) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            foreach ($this->getElementGroups($parent) as $key => $elementGroup) {
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
                ->column(key: 'username', label: __('username'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'status', label: __('status'), canBeHidden: false, sortable: true)
                ->column(key: 'roles', label: __('roles'), canBeHidden: false, sortable: true)
                ->defaultSort('username');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('sysadmin.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('sysadmin.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $users): AnonymousResourceCollection
    {
        return UserResource::collection($users);
    }


    public function htmlResponse(LengthAwarePaginator $users, ActionRequest $request): Response
    {

        return Inertia::render(
            'CRM/Users',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
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
                            'label' => __('create user'),
                            'route' => [
                                'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ]
                ],


                'labels' => [
                    'usernameNoSet' => __('username no set')
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => UsersTabsEnum::navigation(),
                ],

                UsersTabsEnum::USERS->value => $this->tab == UsersTabsEnum::USERS->value ?
                    fn () => UserResource::collection($users)
                    : Inertia::lazy(fn () => UserResource::collection($users)),

                /*
                UsersTabsEnum::USERS_REQUESTS->value => $this->tab == UsersTabsEnum::USERS_REQUESTS->value ?
                    fn () => OrganisationUserRequestLogsResource::collection(IndexUserRequestLogs::run($request->get('sort')))
                    : Inertia::lazy(fn () => OrganisationUserRequestLogsResource::collection(IndexUserRequestLogs::run())),
                */

                UsersTabsEnum::USERS_HISTORIES->value => $this->tab == UsersTabsEnum::USERS_HISTORIES->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(User::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(User::class)))

            ]
        )->table(
            $this->tableStructure(
                $this->parent,
                prefix: 'users',
                exportLinks: [
                    'export' => [
                        'route' => [
                            'name' => 'export.users.index'
                        ]
                    ]
                ]
            )
        )
            /*
            ->table(IndexUserRequestLogs::make()->tableStructure())
            ->table(
                IndexHistories::make()->tableStructure(
                    exportLinks: [
                        'export' => [
                            'route' => [
                                'name' => 'export.histories.index'
                            ]
                        ]
                    ]
                )
            );
            */;
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(UsersTabsEnum::values());

        $this->parent = organisation();

        return $this->handle($this->parent, prefix: 'users');
    }

    public function inCustomer(Customer $customer, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(UsersTabsEnum::values());
        $this->parent = $customer;

        return $this->handle($customer, prefix: 'users');
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(UsersTabsEnum::values());
        $this->parent = $customer;

        return $this->handle($customer, prefix: 'users');
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
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
            'org.crm.customers.show.web-users.index' =>
            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.customers.show', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.customers.show.web-users.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),
            'org.crm.shop.customers.show.web-users.index' =>
            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.shop.customers.show', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.customers.show.web-users.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),

            default => []
        };
    }

}
