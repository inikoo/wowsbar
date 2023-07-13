<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\ContentBlock\UI\IndexContentBlocks;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Models\Portfolio\Website;
use App\Models\Tenancy\Tenant;
use App\Models\Web\WebBlockType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class IndexBanners extends InertiaAction
{
    private Tenant|Website $parent;

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

        return $this->handle();
    }

    public function inWebsite(Website $website, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = $website;

        return $this->handle();
    }


    public function handle($prefix = null): LengthAwarePaginator
    {
        $this->webBlockType = WebBlockType::where('slug', 'banner')->first();

        return IndexContentBlocks::run(
            prefix: $prefix,
            webBlockType: $this->webBlockType
        );
    }


    public function jsonResponse(): AnonymousResourceCollection
    {
        return ContentBlockResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $banners, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/Banners',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('banners'),
                'pageHead'    => [
                    'title'   => __('banners'),
                    'iconRight'    => [
                        'title' => __('banner'),
                        'icon'  => 'fal fa-window-maximize'
                    ],
                    'actions' => [
                        $this->canEdit and class_basename($this->parent) == 'Website'
                        && [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('Create banner'),
                            'label'   => __('new banner'),
                            'route'   => [
                                'name' => 'portfolio.banners.create',
                            ]
                        ],


                    ]
                ],
                'data'        => ContentBlockResource::collection($banners),

            ]
        )->table(
            IndexContentBlocks::make()->tableStructure(
                webBlockType: $this->webBlockType

            )
        );
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
            'portfolio.banners.index' =>
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'portfolio.banners.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
