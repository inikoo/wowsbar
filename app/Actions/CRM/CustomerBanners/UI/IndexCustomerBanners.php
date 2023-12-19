<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Dec 2023 20:36:50 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\CustomerBanners\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Catalogue\ShowCaaSDashboard;
use App\Enums\UI\Organisation\BannersTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\BannersResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolios\CustomerWebsite;
use App\Models\SysAdmin\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCustomerBanners extends InertiaAction
{
    private Organisation|Customer|CustomerWebsite $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return $request->user()->hasPermissionTo('crm.view');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(BannersTabsEnum::values());

        return $this->handle(parent:organisation(), prefix:BannersTabsEnum::BANNERS->value);
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Organisation|Customer|CustomerWebsite $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent = $parent;

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('banners.name', $value);
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Banner::class);

        if(class_basename($parent)=='CustomerWebsite') {
            $queryBuilder->where('portfolio_website_id', $parent->id);
        } elseif(class_basename($parent)=='Customer') {
            $queryBuilder->where('customer_id', $parent->id);
        }


        return $queryBuilder
            ->defaultSort('-date')
            ->select(
                'banners.slug',
                'banners.state',
                'banners.name',
                'banners.image_id',
                'banners.date',
                'banners.ulid'
            )
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(Organisation|Customer|CustomerWebsite $parent, ?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks, $parent) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }


            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('No banners found'),
                        'count' => 0
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'state', label: ['fal', 'fa-yin-yang'], type: 'icon')
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'image_thumbnail', label: ['fal', 'fa-image'])
                ->column(key: 'date', label: __('date'), sortable: true)
                ->defaultSort('-date');
        };
    }


    public function htmlResponse(LengthAwarePaginator $banners, ActionRequest $request): Response
    {
        $scope     = $this->parent;
        $container = null;


        if (class_basename($scope) == 'PortfolioWebsite') {
            $container = [
                'icon'    => ['fal', 'fa-globe'],
                'tooltip' => __('website'),
                'label'   => Str::possessive($scope->name)
            ];
        }

        return Inertia::render(
            'Catalogue/CaaS/Banners',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('banners'),
                'pageHead'    => [
                    'title'     => __('banners'),
                    'container' => $container,
                    'iconRight' => [
                        'title' => __('banner'),
                        'icon'  => 'fal fa-sign'
                    ],


                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => BannersTabsEnum::navigation()
                ],

                BannersTabsEnum::BANNERS->value => $this->tab == BannersTabsEnum::BANNERS->value ?
                    fn () => BannersResource::collection($banners)
                    : Inertia::lazy(fn () => BannersResource::collection($banners)),

                BannersTabsEnum::CHANGELOG->value => $this->tab == BannersTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(PortfolioWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(PortfolioWebsite::class)))
            ]
        )->table(
            $this->tableStructure(
                parent: $this->parent,
                prefix: BannersTabsEnum::BANNERS->value,
            )
        )->table(IndexHistory::make()->tableStructure());
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('banners'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.caas.banners.index' =>
            array_merge(
                ShowCaaSDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'org.caas.banners.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
