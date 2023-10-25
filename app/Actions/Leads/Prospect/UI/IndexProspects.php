<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\UI\CRM\ShowCRMDashboard;
use App\Http\Resources\CRM\ProspectResource;
use App\InertiaTable\InertiaTable;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProspects extends InertiaAction
{
    private Shop|Organisation $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.prospects.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('crm.prospects.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = organisation();

        return $this->handle($this->parent);
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = $shop;

        return $this->handle($shop);
    }

    public function handle(Organisation|Shop $parent, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('prospects.name', '~*', "\y$value\y")
                    ->orWhere('prospects.email', '=', $value)
                    ->orWhere('prospects.phone', '=', $value)
                    ->orWhere('prospects.contact_website', '=', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $query = QueryBuilder::for(Prospect::class);

        if (class_basename($parent) == 'Shop') {
            $query->where('shop_id', $parent->id);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $query
            ->defaultSort('prospects.name')
            ->with('shop')
            ->allowedSorts(['name', 'email', 'phone', 'contact_website'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
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
                        'title'       => __('no prospect'),
                        'description' => null,
                        'count'       => 0
                    ]
                )
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'phone', label: __('phone'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'website', label: __('website'), canBeHidden: false, sortable: true, searchable: true);
        };
    }

    public function jsonResponse(LengthAwarePaginator $prospects): AnonymousResourceCollection
    {
        return ProspectResource::collection($prospects);
    }

    public function htmlResponse(LengthAwarePaginator $prospects, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/Prospects',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters(),
                ),
                'title'        => __('prospects'),
                'pageHead'     => [
                    'title'     => __('prospects'),
                    'iconRight' => [
                        'icon'  => ['fal', 'fa-user-plus'],
                        'title' => __('prospect')
                    ],
                    'actions'   => [
                        $this->canEdit ? [
                            'type'    => 'buttonGroup',
                            'buttons' =>
                                match (class_basename($this->parent)) {
                                    'Shop' => [
                                        [
                                            'style' => 'secondary',
                                            'icon'  => ['fal', 'fa-upload'],
                                            'label' => 'upload',
                                            'route' => [
                                                'name'       => 'org.models.shop.prospects.upload',
                                                'parameters' => $this->parent->id

                                            ],
                                        ],
                                        [
                                            'type'  => 'button',
                                            'style' => 'create',
                                            'label' => __('prospect'),
                                            'route' => [
                                                'name'       => 'org.crm.shop.prospects.create',
                                                'parameters' => array_values($this->originalParameters)
                                            ]
                                        ]
                                    ],
                                    default => []
                                }


                        ] : false
                    ]
                ],
                'uploadRoutes' => [
                    'upload' => [
                        'name'       => 'org.models.shop.prospects.upload',
                        'parameters' => $this->parent->id
                    ],
                    // 'history' => [
                    //     'name'       => 'org.models.prospects.upload',
                    //     'parameters' => $this->parent->id
                    // ],
                    // 'download' => [
                    //     'name'       => 'org.crm.prospects.uploads.template.download',
                    //     'parameters' => $this->parent->id
                    // ]
                ],
                'data'         => ProspectResource::collection($prospects),


            ]
        )->table($this->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('prospects'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.crm.prospects.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs(
                    'crm.dashboard',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name' => 'org.crm.prospects.index',
                    ]
                ),
            ),
            'org.crm.shop.prospects.index' =>
            array_merge(
                (new ShowCRMDashboard())->getBreadcrumbs(
                    'org.crm.shop.dashboard',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.prospects.index',
                        'parameters' => $routeParameters
                    ]
                )
            ),
            default => []
        };
    }
}
