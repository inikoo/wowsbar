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
use App\Http\Resources\Portfolio\AnnouncementsResource;
use App\InertiaTable\InertiaTable;
use App\Models\Announcement;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioWebsite;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

        $queryBuilder = QueryBuilder::for(Announcement::class);

        return $queryBuilder
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

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withExportLinks($exportLinks)
                ->column(key: 'code', label: __('code'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true);
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

    public function htmlResponse(LengthAwarePaginator $announcements, ActionRequest $request): Response
    {
        return Inertia::render(
            'Banners/Announcements',
            [
                'breadcrumbs' => [],
                'title'       => __('Announcements'),
                'pageHead'    => [
                    'title'     => __('Announcements'),
                    'iconRight' => [
                         'title' => __('announcements'),
                        'icon'   => 'fal fa-sign'
                    ],
                    'actions'   =>
                        [
                            [
                                'type'    => 'button',
                                'style'   => 'create',
                                'tooltip' => __('new announcement'),
                                'label'   => __('announcement'),
                                'route'   => [
                                    'name'       => 'customer.portfolio.announcements.create',
                                    'parameters' => []
                                ]
                            ]
                        ]
                ],
                'data' => AnnouncementsResource::collection($announcements)
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
