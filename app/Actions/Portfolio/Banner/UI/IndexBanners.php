<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\UI\Authenticated\Portfolio\ShowPortfolio;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Http\Resources\Portfolio\BannerResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
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
            $query->where('banners.code', "%$value%");
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Banner::class);

        if (class_basename($parent) == 'PortfolioWebsite') {
            $queryBuilder->leftJoin('banner_portfolio_website', 'banner_id', 'banners.id')
                ->where('banner_portfolio_website.portfolio_website_id', $parent->id);
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
            ->defaultSort('banners.code')
            //            ->select(['banners.code', 'banners.name', 'banners.slug'])
            ->allowedSorts(['slug', 'code', 'name', 'created_at', 'updated_at'])
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
                if (customer()->stats->number_websites == 0) {
                    $description = __('Before creating your first banner you need a website').' 😉';

                    $action = [
                        'type'    => 'button',
                        'style'   => 'primary',
                        'tooltip' => __('new website'),
                        'label'   => __('website'),
                        'route'   => [
                            'name' => 'tenant.portfolio.websites.create',
                        ]
                    ];
                }
            }

            $emptyState = [
                'title'       => __('No banners found'),
                'count'       => customer()->stats->number_content_blocks_web_block_type_banner,
                'description' => $description,
                'action'      => $action
                /*
                'action' => $canEdit && class_basename($parent) == 'PortfolioWebsite' ? [
                    'type'    => 'button',
                    'style'   => 'primary',
                    'tooltip' => __('new banner'),
                    'label'   => __('banner'),
                    'route'   => [
                        'name'       => 'tenant.portfolio.websites.show.banners.create',
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
                ->column(key: 'created_at', label: __('Date Created'), sortable: true)
                ->column(key: 'updated_at', label: __('Date Updated'), sortable: true)
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
            'Portfolio/Banners',
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
                    'actions'   => [
                        match (customer()->stats->number_websites) {
                            1 => [
                                'type'  => 'button',
                                'style' => 'create',
                                'label' => __('create banner'),
                                'route' => [
                                    'name'       => 'tenant.portfolio.websites.show.banners.create',
                                    'parameters' => customer()->portfolioWebsites()->first()->slug
                                ]
                            ],
                            default => [
                                'type'  => 'button',
                                'style' => 'create',
                                'label' => __('create banner'),
                                'route' => [
                                    'name' => 'tenant.portfolio.banners.create',
                                ]
                            ]
                        }
                    ]

                ],

                'data' => BannerResource::collection($banners),
            ]
        )->table(
            $this->tableStructure(
                parent: $this->parent,
                /*
                modelOperations: [
                    'createLink' => $this->canEdit ? [
                        'route' => [
                            'name'       => 'tenant.portfolio.websites.show.banners.create',
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
            'tenant.portfolio.banners.index' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'tenant.portfolio.banners.index'
                    ]
                ),
            ),
            'tenant.portfolio.websites.show.banners.index' =>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'tenant.portfolio.websites.show',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name'       => 'tenant.portfolio.websites.show.banners.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),
            default => []
        };
    }
}