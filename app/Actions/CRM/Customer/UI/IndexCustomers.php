<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\UI\CRM\ShowCRMDashboard;
use App\Http\Resources\CRM\CustomerResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCustomers extends InertiaAction
{
    protected Organisation|Shop $parent;


    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle(organisation());
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle($shop);
    }


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()>hasPermissionTo('crm.customers.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('crm.customers.view')
            );
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Organisation|Shop $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent = $parent;

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('customers.name', '~*', "\y$value\y")
                    ->orWhere('customers.email', 'ILIKE', "%$value")
                    ->orWhere('customers.reference', '=', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Customer::class);
        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        return $queryBuilder
            ->defaultSort('customers.slug')
            ->select([
                'reference',
                'customers.id',
                'customers.name',
                'customers.slug',
                'shops.code as shop_code',
                'shops.slug as shop_slug'
            ])
            ->leftJoin('customer_stats', 'customers.id', 'customer_stats.customer_id')
            ->leftJoin('shops', 'shops.id', 'shop_id')
            ->when(true, function ($query) use ($parent) {
                if (class_basename($parent) == 'Shop') {
                    $query->where('customers.shop_id', $parent->id);
                }
            })
            ->allowedSorts(['reference', 'name', 'slug'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure($parent, ?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($parent, $modelOperations, $prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }


            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    match (class_basename($parent)) {
                        'Shop' => [
                            'title'       => __("No customers found"),
                            'description' => $this->canEdit ? __('Get started by creating a new customer.') : null,
                            'count'       => $parent->crmStats->number_customers,
                            'action'      => [
                                'type'    => 'button',
                                'style'   => 'create',
                                'tooltip' => __('new customer'),
                                'label'   => __('customer'),
                                'route'   => [
                                    'name'       => 'org.crm.shop.customers.create',
                                    'parameters' => [$parent->slug]
                                ]
                            ]
                        ],
                        default => null
                    }
                )
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true);
        };
    }

    public function jsonResponse(LengthAwarePaginator $customers): AnonymousResourceCollection
    {
        return CustomerResource::collection($customers);
    }

    public function htmlResponse(LengthAwarePaginator $customers, ActionRequest $request): Response
    {
        $scope     = $this->parent;
        $container = null;
        if (class_basename($scope) == 'Shop' and organisation()->stats->number_shops > 1) {
            $container = [
                'icon'    => ['fal', 'fa-store-alt'],
                'tooltip' => __('Shop'),
                'label'   => Str::possessive($scope->name)
            ];
        }

        return Inertia::render(
            'CRM/Customers',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('customers'),
                'pageHead'    => [
                    'title'     => __('customers'),
                    'container' => $container,
                    'iconRight' => [
                        'icon'  => ['fal', 'fa-user'],
                        'title' => __('customer')
                    ],
                    'actions'   =>
                        [
                            $this->canEdit ? [
                                'type'    => 'button',
                                'style'   => 'create',
                                'tooltip' => __('new customer'),
                                'label'   => __('customer'),
                                'route'   => [
                                    'name'       => 'org.crm.shop.customers.create',
                                    'parameters' => array_values($this->originalParameters)
                                ]
                            ] : null
                        ]
                ],
                'data'        => CustomerResource::collection($customers),

            ]
        )->table($this->tableStructure($this->parent));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('customers'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.crm.customers.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name' => 'org.crm.customers.index',
                        null
                    ]
                ),
            ),
            'org.crm.shop.customers.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.shop.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.customers.index',
                        'parameters' => $routeParameters
                    ]
                )
            ),
            default => []
        };
    }
}
