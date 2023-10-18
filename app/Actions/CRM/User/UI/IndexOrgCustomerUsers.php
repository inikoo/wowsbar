<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 14:02:17 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User\UI;

use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Enums\UI\UsersTabsEnum;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\CRM\OrgCustomerUsersResource;
use App\Http\Resources\History\HistoryResource;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexOrgCustomerUsers extends InertiaAction
{
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
        $this->parent = $parent;
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('contact_name', $value)
                    ->orWhere('users.username', 'ILIKE', "$value%");
            });
        });


        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(CustomerUser::class);
        $queryBuilder->leftJoin('users', 'users.id', 'customer_user.user_id');

        if (class_basename($parent) == 'Customer') {
            $queryBuilder->where('customer_id', $parent->id);
        }

        foreach ($this->getElementGroups($parent) as $key => $elementGroup) {
            /** @noinspection PhpUndefinedMethodInspection */
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        $roles = DB::table('model_has_roles')
            ->select(
                'model_id',
                DB::raw('jsonb_agg(json_build_object(\'name\',roles.name)) as roles')
            )
            ->leftJoin('roles', 'model_has_roles.role_id', 'roles.id')
            ->where('model_type', 'CustomerUser')
            ->groupBy('model_id');
        $queryBuilder->leftJoinSub($roles, 'roles', function (JoinClause $join) {
            $join->on('customer_user.id', '=', 'roles.model_id');
        });

        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->select(
                'customer_user.slug',
                'roles',
                'avatar_id',
                'email',
                'contact_name',
                'customer_user.status',
                'is_root'
            )
            ->defaultSort('customer_user.slug')
            ->allowedSorts(['customer_user.slug', 'email', 'contact_name'])
            ->allowedFilters([$globalSearch, 'email', 'contact_name'])
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
                ->column(key: 'avatar', label: ['fal', 'fa-user-circle'], type: 'avatar')
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'status', label: __('status'), canBeHidden: false, sortable: true)
                ->column(key: 'roles', label: __('roles'), canBeHidden: false, sortable: true)
                ->defaultSort('username');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return
            (
                $request->user()->hasPermissionTo('crm.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $users): AnonymousResourceCollection
    {
        return UserResource::collection($users);
    }


    public function htmlResponse(LengthAwarePaginator $users, ActionRequest $request): Response
    {
        $scope     = $this->parent;
        $container = null;


        if (class_basename($scope) == 'Shop' and organisation()->stats->number_shops > 1) {
            $container = [
                'icon'    => ['fal', 'fa-store-alt'],
                'tooltip' => __('Shop'),
                'label'   => Str::possessive($scope->name)
            ];
        } elseif (class_basename($scope) == 'Customer') {
            $container = [
                'icon'    => ['fal', 'fa-user'],
                'tooltip' => __('Customer'),
                'label'   => Str::possessive($scope->name),
                'href'    => [
                    'name'       => 'org.crm.shop.customers.show',
                    'parameters' => $request->route()->originalParameters()
                ]
            ];
        }


        return Inertia::render(
            'CRM/OrganisationCustomerUsers',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('users'),
                'pageHead'    => [
                    'container' => $container,
                    'title'     => __('users'),
                    'iconRight' => [
                        'title' => __('users'),
                        'icon'  => 'fal fa-terminal'
                    ],
                    'actions'   => [
                        [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('create customer user'),
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
                    fn () => OrgCustomerUsersResource::collection($users)
                    : Inertia::lazy(fn () => OrgCustomerUsersResource::collection($users)),

                /*
                UsersTabsEnum::USERS_REQUESTS->value => $this->tab == UsersTabsEnum::USERS_REQUESTS->value ?
                    fn () => OrganisationUserRequestLogsResource::collection(IndexUserRequestLogs::run($request->get('sort')))
                    : Inertia::lazy(fn () => OrganisationUserRequestLogsResource::collection(IndexUserRequestLogs::run())),
                */

                UsersTabsEnum::USERS_HISTORIES->value => $this->tab == UsersTabsEnum::USERS_HISTORIES->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(User::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(User::class)))

            ]
        )->table(
            $this->tableStructure(
                $this->parent,
                prefix: UsersTabsEnum::USERS->value,
            )
        )/*
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
            */ ;
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
            'org.crm.customers.show.customer-users.index' =>
            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.customers.show', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.customers.show.customer-users.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),
            'org.crm.shop.customers.show.customer-users.index' =>
            array_merge(
                ShowCustomer::make()->getBreadcrumbs('org.crm.shop.customers.show', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.customers.show.customer-users.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),

            default => []
        };
    }

}
