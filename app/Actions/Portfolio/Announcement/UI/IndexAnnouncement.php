<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement\UI;

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

class IndexAnnouncement extends InertiaAction
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

        $queryBuilder->join('banner_stats', 'banner_stats.banner_id', 'banners.id')
            ->addSelect('banner_stats.number_views');

        if (class_basename($parent) == 'PortfolioWebsite') {
            $queryBuilder->leftJoin('banner_portfolio_website', 'banner_portfolio_website.banner_id', 'banners.id')
                ->where('banner_portfolio_website.portfolio_website_id', $parent->id);
        } else {

            $websites = DB::table('banner_portfolio_website')
                ->select(
                    'banner_id',
                    DB::raw('jsonb_agg(json_build_object(\'slug\',portfolio_websites.slug,\'name\',portfolio_websites.name)) as websites')
                )
                ->leftJoin('portfolio_websites', 'banner_portfolio_website.portfolio_website_id', 'portfolio_websites.id')
                ->groupBy('banner_portfolio_website.banner_id');

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
            ->allowedSorts(['name', 'date', 'number_views'])
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
                ->column(key: 'number_views', label: __('views'), sortable: true)
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
            'Banners/Announcement',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('Announcement'),
                'pageHead'    => [
                    'title'     => __('Announcement'),
                    'container' => $container,
                    'iconRight' => [
                        // 'title' => __('banner'),
                        'icon'  => 'fal fa-sign'
                    ],
                ],
                'firstBanner'      => $this->canEdit ? $this->getFirstBannerWidget($scope) : null,
                'announcementData' => [
                    "id"   => 1,
                    "code" => "announcement1abc",
                    // "scope" => "webpage",
                    "name"       => "Announcement Simple",
                    "created_at" => null,
                    "updated_at" => null,
                    "icon"       => "fal fa-presentation",
                    "fields"     => [
                        "text_1" => [
                            "text" => '<p>GeneriCon 2023 is on June 7 – 9 in Denver. <a href="#" class="whitespace-nowrap font-semibold">Get your
                    ticket&nbsp;<span aria-hidden="true">&rarr;</span></a></p>',
                        ],
                        "text_2" => [
                            "text" => "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed laoreet nisi at elit venenatis fringilla. Cras ut semper quam, sit.</p>",
                        ],
                        "button_1" => [
                            "label"      => "Click me",
                            "url"        => "https://example.com",
                            "target"     => "_blank",
                            "border"     => "1px solid rgba(245, 12, 89, 0.7)",
                            "bg_color"   => "rgba(245, 12, 89, 0.7)",
                            "text_color" => "rgba(255, 255, 255, 1)",
                            "width"      => "full"
                        ],
                        "close_button" => [
                            "position_top"  => "50%",
                            "position_left" => "50%",
                            "text_color"    => "rgba(0, 0, 0, 0.5)",
                            "size"          => "0.5"
                        ]
                    ],
                    "container_properties" => [
                        "link" => [
                            "href"   => "",
                            "target" => "_blank",
                        ],
                        "border" => [
                            "top" => [
                                "value" => 0
                            ],
                            "left" => [
                                "value" => 0
                            ],
                            "unit"  => "px",
                            "color" => "#000000",
                            "right" => [
                                "value" => 0
                            ],
                            "bottom" => [
                                "value" => 0
                            ],
                            "rounded" => [
                                "unit"    => "px",
                                "topleft" => [
                                    "value" => 0
                                ],
                                "topright" => [
                                    "value" => 0
                                ],
                                "bottomleft" => [
                                    "value" => 0
                                ],
                                "bottomright" => [
                                    "value" => 0
                                ]
                            ]
                        ],
                        "margin" => [
                            "top" => [
                                "value" => 0
                            ],
                            "left" => [
                                "value" => 0
                            ],
                            "unit"  => "px",
                            "right" => [
                                "value" => 0
                            ],
                            "bottom" => [
                                "value" => 0
                            ]
                        ],
                        "padding" => [
                            "top" => [
                                "value" => 0
                            ],
                            "left" => [
                                "value" => 0
                            ],
                            "unit"  => "px",
                            "right" => [
                                "value" => 0
                            ],
                            "bottom" => [
                                "value" => 0
                            ]
                        ],
                        "background" => [
                            "type"  => "color",
                            "color" => "linear-gradient(to right, #ff80b5, #9089fc)",
                            "image" => [
                                "original" => null
                            ]
                        ]
                    ]
                ],

                // 'data' => BannersResource::collection($banners),
            ]
        )->table(
            $this->tableStructure(
                parent: $this->parent,
                canEdit: $this->canEdit,
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
