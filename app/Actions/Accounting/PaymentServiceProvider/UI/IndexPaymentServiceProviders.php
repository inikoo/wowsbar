<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Accounting\AccountingDashboard;
use App\Http\Resources\Accounting\PaymentServiceProviderResource;
use App\InertiaTable\InertiaTable;
use App\Models\Accounting\PaymentServiceProvider;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexPaymentServiceProviders extends InertiaAction
{
    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('payment_service_providers.code', 'ILIKE', "%$value%")
                    ->orWhere('payment_service_providers.name', 'ILIKE', "%$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(PaymentServiceProvider::class);

        $queryBuilder->leftJoin(
            'country_payment_service_provider',
            'country_payment_service_provider.payment_service_provider_id',
            'payment_service_providers.id'
        )->where('country_payment_service_provider.country_id', organisation()->country_id)
        ->orWhere('show_marketplace', true);

        /*
        $queryBuilder->where('show_marketplace', true)
            ->orWhere(function ($query) {
                $query->whereNull('show_marketplace')
                    ->where(
                        function ($query) {
                            $query->from('country_payment_service_provider')
                                ->whereColumn('country_payment_service_provider.payment_service_provider_id',
                                    'payment_service_providers.id')
                                ->count();
                        }
                        , 1
                    );
            });
*/

        return $queryBuilder
            ->defaultSort('payment_service_providers.code')
            ->select(['code', 'name'])
            ->allowedSorts(['code', 'name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure($prefix = null): Closure
    {
        return function (InertiaTable $table) use ($prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }
            $table
                ->withGlobalSearch()
                ->defaultSort('code')
                //  ->column(key: 'code', label: __('code'), canBeHidden: false, sortable: true )
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true)
                ->column(key: 'actions', label: __('actions'), canBeHidden: false);
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('accounting.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('accounting.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $paymentServiceProviders): AnonymousResourceCollection
    {
        return PaymentServiceProviderResource::collection($paymentServiceProviders);
    }


    public function htmlResponse(LengthAwarePaginator $paymentServiceProviders): Response
    {
        return Inertia::render(
            'Accounting/PaymentServiceProviders',
            [
                'breadcrumbs'               => $this->getBreadcrumbs(),
                'title'                     => __('Payment Service Providers'),
                'pageHead'                  => [
                    'title' => __('Payment Service Providers'),

                ],
                'payment_service_providers' => PaymentServiceProviderResource::collection($paymentServiceProviders),


            ]
        )->table($this->tableStructure());
    }


    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle();
    }

    public function getBreadcrumbs($suffix = null): array
    {
        return array_merge(
            AccountingDashboard::make()->getBreadcrumbs('org.accounting.dashboard.show', []),
            [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => [
                            'name' => 'org.accounting.payment-service-providers.index',
                        ],
                        'label' => __('providers'),
                        'icon'  => 'fal fa-bars',
                    ],
                    'suffix' => $suffix
                ],
            ]
        );
    }
}
