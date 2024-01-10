<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 21:20:25 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Shipping\ShipperAccount\UI;

use App\Actions\InertiaAction;
use App\Actions\SysAdmin\UI\CRM\ShowCRMDashboard;
use App\Enums\UI\Customer\CustomerTabsEnum;
use App\Http\Resources\CRM\ShipperAccountResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\ShipperAccount;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexShipperAccounts extends InertiaAction
{
    private Customer $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return
            (
                $request->user()->hasPermissionTo('crm.view')
            );
    }



    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Customer $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent = $parent;

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('label', '~*', "\y$value\y");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(ShipperAccount::class);

        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        //         $queryBuilder->leftJoin('customers', 'appointments.customer_id', 'customers.id');

        return $queryBuilder
            ->defaultSort('-created_at')
            ->allowedSorts(['created_at'])
            ->when(true, function ($query) use ($parent) {
                $query->where('customer_id', $parent->id);
            })
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(Customer $parent, ?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks, $parent) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix . 'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title'       => __('No shipper accounts found!'),
                        'count'       => $parent->shipperAccounts()->count(),
                        'action'      => [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new shipper account'),
                            'label'   => __('shipper account'),
                            'route'   => [
                                'name'       => 'org.crm.shop.customers.shipper-accounts.create',
                                'parameters' => [
                                    $parent->shop->slug,
                                    $parent->slug
                                ]
                            ]
                        ]
                    ]
                );
            if ($exportLinks) {
                $table->withExportLinks($exportLinks);
            }

            $table->column(key: 'slug', label: 'slug')
                ->column(key: 'label', label: __('label'))
                ->defaultSort('created_at');
        };
    }

    public function inCustomerInShop(Shop $shop, Customer $customer, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        return $this->handle($customer);
    }

    public function htmlResponse(LengthAwarePaginator $shipperAccounts, ActionRequest $request): Response
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
            'CRM/ShipperAccounts',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('shipper accounts'),
                'pageHead'    => [
                    'title'     => __('shipper accounts'),
                    'container' => $container,
                    'iconRight' => [
                        'icon'  => ['fal', 'fa-shipping-fast'],
                        'title' => __('shipper accounts')
                    ],
                    'actions'   =>
                        [
                            $this->canEdit ? [
                                'type'    => 'button',
                                'style'   => 'create',
                                'tooltip' => __('new shipper accounts'),
                                'label'   => __('shipper accounts'),
                                'route'   => [
                                    'name'       => 'org.crm.shop.shipper-accounts.create',
                                    'parameters' => array_values($this->originalParameters)
                                ]
                            ] : []
                        ]
                ],
                'data'        => ShipperAccountResource::collection($shipperAccounts),

            ]
        )->table($this->tableStructure(parent: $this->parent, prefix: CustomerTabsEnum::SHIPPER_ACCOUNTS));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('appointments'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.crm.shipper-accounts.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name' => 'org.crm.appointments.index',
                        null
                    ]
                ),
            ),
            'org.crm.shop.shipper-accounts.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs('org.crm.shop.dashboard', $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.appointments.index',
                        'parameters' => $routeParameters
                    ]
                )
            ),
            default => []
        };
    }


}
