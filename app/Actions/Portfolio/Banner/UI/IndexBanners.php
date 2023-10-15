<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\Traits\WelcomeWidgets\WithFirstBanner;
use App\Actions\UI\Customer\CaaS\ShowCaaSDashboard;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Http\Resources\Portfolio\BannersResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexBanners extends InertiaAction
{
    use WithFirstBanner;
    private Customer|PortfolioWebsite $parent;


    protected array $elementGroups = [];

    protected function getElementGroups(): array
    {
        return
            [
                'state' => [
                    'label'    => __('State'),
                    'elements' => array_merge_recursive(
                        BannerStateEnum::labels(),
                        BannerStateEnum::count()
                    ),

                    'engine' => function ($query, $elements) {
                        $query->whereIn('banners.state', $elements);
                    }
                ]
            ];
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Customer|PortfolioWebsite $parent, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where('banners.slug', "%$value%");
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Banner::class);

        if (class_basename($parent) == 'PortfolioWebsite') {
            $queryBuilder->leftJoin('banner_portfolio_website', 'banner_id', 'banners.id')
                ->where('banner_portfolio_website.portfolio_website_id', $parent->id);
        } else {
            $websites = DB::table('banner_portfolio_website')
                ->select('banner_id', DB::raw('jsonb_agg(json_build_object(\'slug\',portfolio_websites.slug,\'name\',portfolio_websites.name)) as websites'))
               ->leftJoin('portfolio_websites', 'banner_portfolio_website.portfolio_website_id', 'portfolio_websites.id')
                ->groupBy('banner_id');

            $queryBuilder->joinSub($websites, 'websites', function (JoinClause $join) {
                $join->on('banners.id', '=', 'websites.banner_id');
            });

        }





        foreach ($this->getElementGroups() as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        return $queryBuilder
            ->defaultSort('banners.slug')
            ->select(
                'websites',
                'banners.slug',
                'banners.state',
                'banners.name',
                'banners.image_id',
                'live_at',
                'retired_at',
                'banners.created_at',
                'banners.updated_at',
                'banners.live_at',
                'banners.retired_at'
            )
            ->allowedSorts(['slug', 'name', 'created_at', 'updated_at'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(
        Customer|PortfolioWebsite $parent,
        ?array $modelOperations = null,
        $prefix = null,
        $canEdit = false,
        ?array $exportLinks = null
    ): Closure {
        return function (InertiaTable $table) use ($modelOperations, $parent, $prefix, $canEdit, $exportLinks) {
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

            $action = null;

            $description = null;
            if ($canEdit) {
                if (customer()->portfolioStats->number_portfolio_websites == 0) {
                    $description = __('Before creating your first banner you need a website').' 😉';

                    $action = [
                        'type'    => 'button',
                        'style'   => 'primary',
                        'tooltip' => __('new website'),
                        'label'   => __('website'),
                        'route'   => [
                            'name' => 'customer.portfolio.websites.create',
                        ]
                    ];
                }
            }

            $emptyState = [
                'title'       => __('No banners found'),
                'count'       => customer()->portfolioStats->number_banners,
                'description' => $description,
                'action'      => $action
                /*
                'action' => $canEdit && class_basename($parent) == 'PortfolioWebsite' ? [
                    'type'    => 'button',
                    'style'   => 'primary',
                    'tooltip' => __('new banner'),
                    'label'   => __('banner'),
                    'route'   => [
                        'name'       => 'customer.portfolio.websites.show.banners.create',
                        'parameters' => ['website' => $parent->slug]
                    ]
                ] : null
                */
            ];


            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState($emptyState)
                ->withExportLinks($exportLinks)
                ->column(key: 'state', label: ['fal', 'fa-yin-yang'])
                ->column(key: 'slug', label: __('code'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'image_thumbnail', label: ['fal', 'fa-image'])
                ->column(key: 'websites', label: __('websites'))
                ->column(key: 'date', label: __('date'), sortable: true)
                ->defaultSort('slug');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->get('customerUser')->hasPermissionTo('portfolio.banners.view')
            );
    }

    public function inCustomer(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = customer();

        return $this->handle($this->parent);
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = $portfolioWebsite;

        return $this->handle($this->parent);
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
            'Banners/Banners',
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
                        'icon'  => 'fal fa-rectangle-wide'
                    ],
                    'actions'   =>
                        [
                            [
                                'type'  => 'button',
                                'style' => 'create',
                                'label' => __('create banner'),
                                'route' => [
                                    'name' => 'customer.caas.banners.create',
                                ]
                            ]
                        ]

                ],
                'firstBanner' => $this->canEdit ? $this->getFirstBannerWidget($scope) : null,


                'data' => BannersResource::collection($banners),
            ]
        )->table(
            $this->tableStructure(
                parent: $this->parent,
                /*
                modelOperations: [
                    'createLink' => $this->canEdit ? [
                        'route' => [
                            'name'       => 'customer.portfolio.websites.show.banners.create',
                            'parameters' => array_values([$this->parent->slug])
                        ],
                        'label' => __('banner'),
                        'style' => 'primary',
                        'icon'  => 'fas fa-plus'
                    ] : false
                ],
                */
                canEdit: $this->canEdit,
                exportLinks: [
                    'export' => [
                        'route' => [
                            'name' => 'export.banners.index'
                        ]
                    ]
                ]
            )
        );
    }

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
            'customer.caas.banners.index' =>
            array_merge(
                ShowCaaSDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'customer.caas.banners.index'
                    ]
                ),
            ),
            'customer.portfolio.websites.show.banners.index' =>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'customer.portfolio.websites.show',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name'       => 'customer.portfolio.websites.show.banners.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),
            default => []
        };
    }
}
