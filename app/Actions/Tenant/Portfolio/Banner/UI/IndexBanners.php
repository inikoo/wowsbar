<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 11:21:24 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolioDashboard;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Http\Resources\Portfolio\BannerResource;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexBanners extends InertiaAction
{
    private Tenant|PortfolioWebsite $parent;


    protected array $elementGroups = [];

    protected function getElementGroups(): void
    {
        $this->elementGroups =
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
    public function handle(Tenant|PortfolioWebsite $parent, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where('banners.code', "%$value%");
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Banner::class);

        if (class_basename($parent) == 'PortfolioWebsite') {
            $queryBuilder->leftJoin('banner_portfolio_website', 'banner_id', 'banners.id')
                ->where('portfolio_website_id', $parent->id);
        }


        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        return $queryBuilder
            ->defaultSort('banners.code')
            ->select(['banners.code', 'banners.name', 'banners.slug'])
            ->allowedSorts(['slug', 'code', 'name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(
        Tenant|PortfolioWebsite $parent,
        ?array $modelOperations = null,
        $prefix = null,
        $canEdit = false
    ): Closure {
        $this->getElementGroups();

        return function (InertiaTable $table) use ($modelOperations, $parent, $prefix, $canEdit) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            foreach ($this->elementGroups as $key => $elementGroup) {
                $table->elementGroup(
                    key: $key,
                    label: $elementGroup['label'],
                    elements: $elementGroup['elements']
                );
            }


            $action = null;

            $description = null;
            if ($canEdit) {
                if (app('currentTenant')->stats->number_websites == 0) {
                    $description = __('Before creating your first banner you need a website').' 😉';

                    $action = [
                        'type'    => 'button',
                        'style'   => 'primary',
                        'tooltip' => __('new website'),
                        'label'   => __('website'),
                        'route'   => [
                            'name' => 'portfolio.websites.create',
                        ]
                    ];
                }
            }

            $emptyState = [
                'title'       => __('No banners found'),
                'count'       => app('currentTenant')->stats->number_content_blocks_web_block_type_banner,
                'description' => $description,
                'action'      => $action
                /*
                'action' => $canEdit && class_basename($parent) == 'PortfolioWebsite' ? [
                    'type'    => 'button',
                    'style'   => 'primary',
                    'tooltip' => __('new banner'),
                    'label'   => __('banner'),
                    'route'   => [
                        'name'       => 'portfolio.websites.show.banners.create',
                        'parameters' => ['website' => $parent->slug]
                    ]
                ] : null
                */
            ];


            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState($emptyState)
                ->column(key: 'slug', label: __('code'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'banner', label: __('banner'), sortable: true)
                ->defaultSort('slug');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('portfolio.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.view')
            );
    }

    public function inTenant(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = app('currentTenant');

        return $this->handle($this->parent);
    }

    public function inPortfolioWebsite(PortfolioWebsite $website, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = $website;

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
            'Tenant/Portfolio/Banners',
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
                        'icon'  => 'fal fa-window-maximize'
                    ],
                    'actions'   =>
                        match (app('currentTenant')->stats->number_websites) {
                            0 => [],
                            1 => [
                                'type'  => 'button',
                                'style' => 'create',
                                'label' => 'create banner',
                                'route' => [
                                    'name'       => 'portfolio.websites.show.banners.create',
                                    'parameters' => app('currentTenant')->portfolioWebsites()->first()->slug
                                ]
                            ],
                            default => [
                                'type'      => 'modal',
                                'component' => 'chooseWebsite',
                                'style'     => 'create',
                                'label'     => 'create banner',
                                'route'     => [
                                    'name' => 'portfolio.banners.create',
                                ]
                            ]
                        }


                ],
                'data'        => BannerResource::collection($banners),

            ]
        )->table(
            $this->tableStructure(
                parent: $this->parent,
                /*
                modelOperations: [
                    'createLink' => $this->canEdit ? [
                        'route' => [
                            'name'       => 'portfolio.websites.show.banners.create',
                            'parameters' => array_values([$this->parent->slug])
                        ],
                        'label' => __('banner'),
                        'style' => 'primary',
                        'icon'  => 'fas fa-plus'
                    ] : false
                ],
                */
                canEdit: $this->canEdit
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
            'portfolio.banners.index' =>
            array_merge(
                ShowPortfolioDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'portfolio.banners.index'
                    ]
                ),
            ),
            'portfolio.websites.show.banners.index' =>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'portfolio.websites.show',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name'       => 'portfolio.websites.show.banners.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),
            default => []
        };
    }
}
