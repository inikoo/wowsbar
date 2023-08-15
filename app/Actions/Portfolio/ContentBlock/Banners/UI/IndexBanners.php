<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\ContentBlock\UI\IndexContentBlocks;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolioDashboard;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use App\Models\Web\WebBlockType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class IndexBanners extends InertiaAction
{
    private Tenant|PortfolioWebsite $parent;

    private WebBlockType $webBlockType;

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

    public function inWebsite(PortfolioWebsite $website, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = $website;

        return $this->handle($this->parent);
    }


    public function handle(Tenant|PortfolioWebsite $parent, $prefix = null): LengthAwarePaginator
    {
        $this->webBlockType = WebBlockType::where('slug', 'banner')->first();

        return IndexContentBlocks::run(
            parent: $parent,
            prefix: $prefix,
            webBlockType: $this->webBlockType
        );
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
                'title'    => __('banners'),
                'pageHead' => [
                    'title'     => __('banners'),
                    'container' => $container,
                    'iconRight' => [
                        'title' => __('banner'),
                        'icon'  => 'fal fa-window-maximize'
                    ],
                    'actions' =>
                        match (app('currentTenant')->stats->number_websites) {
                            0 => [],
                            1 => [
                                'type'  => 'button',
                                'style' => 'create',
                                'label' => 'create banner',
                                'route' => [
                                    'name'       => 'portfolio.websites.show.banners.create',
                                    'parameters' => app('currentTenant')->websites()->first()->slug
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
                'data' => ContentBlockResource::collection($banners),

            ]
        )->table(
            IndexContentBlocks::make()->tableStructure(
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
                webBlockType: $this->webBlockType,
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
