<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Accounting\Invoice;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Accounting\AccountingDashboard;
use App\Http\Resources\Accounting\InvoiceResource;
use App\InertiaTable\InertiaTable;
use App\Models\Accounting\Invoice;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexInvoices extends InertiaAction
{
    public function handle($parent, $prefix=null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('invoices.number', '~*', "\y$value\y")
                    ->orWhere('invoices.total', '=', $value)
                    ->orWhere('invoices.net', '=', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        return QueryBuilder::for(Invoice::class)
            ->defaultSort('invoices.number')
            ->select([
                'invoices.number',
                'invoices.total',
                'invoices.net',
                'invoices.created_at',
                'invoices.updated_at',
                'invoices.slug',
                'shops.slug as shop_slug'
            ])
            ->leftJoin('invoice_stats', 'invoices.id', 'invoice_stats.invoice_id')
            ->allowedSorts(['number', 'total', 'net'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure($prefix=null): Closure
    {
        return function (InertiaTable $table) use ($prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }
            $table
                ->withGlobalSearch()
                ->column(key: 'number', label: __('number'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'total', label: __('total'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'net', label: __('net'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('number');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        return true;
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('shops.products.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $invoices): AnonymousResourceCollection
    {
        return InvoiceResource::collection($invoices);
    }


    public function htmlResponse(LengthAwarePaginator $invoices, ActionRequest $request): Response
    {
        $routeName       = $request->route()->getName();
        $routeParameters = $request->route()->parameters;

        return Inertia::render(
            'Accounting/Invoices',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $routeName,
                    $routeParameters
                ),
                'title'       => __('invoices'),
                'pageHead'    => [
                    'title'     => __('invoices'),
                ],
                'data' => InvoiceResource::collection($invoices),


            ]
        )->table($this->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {

        $headCrumb = function () use ($routeName, $routeParameters) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => [
                            'name'       => $routeName,
                            'parameters' => $routeParameters
                        ],
                        'label' => __('invoices'),
                        'icon'  => 'fal fa-bars',

                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.accounting.shops.show.invoices.index' =>
            array_merge(
                AccountingDashboard::make()->getBreadcrumbs('org.accounting.shops.show.dashboard', $routeParameters),
                $headCrumb()
            ),
            'org.accounting.invoices.index' =>
            array_merge(
                AccountingDashboard::make()->getBreadcrumbs('org.accounting.dashboard', []),
                $headCrumb()
            ),


            default => []
        };


    }
}
