<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\WelcomeWidgets\WithFirstBanner;
use App\Actions\UI\Customer\Banners\ShowBannersDashboard;
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
            $query->where('banners.name', "%$value%");
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Banner::class);
        $queryBuilder->select(
            'banners.slug',
            'banners.state',
            'banners.name',
            'banners.image_id',
            'banners.date'
        );

        if (class_basename($parent) == 'PortfolioWebsite') {
            $queryBuilder->leftJoin('banner_portfolio_website', 'banner_id', 'banners.id')
                ->where('banner_portfolio_website.portfolio_website_id', $parent->id);
        } else {
            $websites = DB::table('banner_portfolio_website')
                ->select(
                    'banner_id',
                    DB::raw('jsonb_agg(json_build_object(\'slug\',portfolio_websites.slug,\'name\',portfolio_websites.name)) as websites')
                )
                ->leftJoin('portfolio_websites', 'banner_portfolio_website.portfolio_website_id', 'portfolio_websites.id')
                ->groupBy('banner_id');

            $queryBuilder->joinSub($websites, 'websites', function (JoinClause $join) {
                $join->on('banners.id', '=', 'websites.banner_id');
            });
            $queryBuilder->addSelect('websites');
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
            ->defaultSort('-date')
            ->allowedSorts(['name', 'date'])
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
            ];


            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState($emptyState)
                ->withExportLinks($exportLinks)
                ->column(key: 'state', label: ['fal', 'fa-yin-yang'], type: 'icon')
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'image_thumbnail', label: ['fal', 'fa-image']);
            if (class_basename($parent) != 'PortfolioWebsite') {
                $table->column(key: 'websites', label: __('websites'));
            }


            $table->column(key: 'date', label: __('date'), sortable: true)
                ->defaultSort('-date');
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
                        'icon'  => 'fal fa-sign'
                    ],
                    'actions'   =>
                        [
                            [
                                'type'  => 'button',
                                'style' => 'create',
                                'label' => __('Create Banner'),
                                'route' => [
                                    'name' => 'customer.banners.banners.create',
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
            'customer.banners.banners.index' =>
            array_merge(
                ShowBannersDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'customer.banners.banners.index'
                    ]
                ),
            ),
            default => []
        };
    }
}
