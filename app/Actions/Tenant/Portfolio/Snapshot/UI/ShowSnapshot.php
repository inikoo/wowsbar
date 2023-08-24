<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Snapshot\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolioDashboard;
use App\Http\Resources\Gallery\ImageResource;
use App\Http\Resources\Portfolio\SnapshotResource;
use App\InertiaTable\InertiaTable;
use App\Models\Media\LandlordMedia;
use App\Models\Organisation\Web\Website;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolio\Snapshot;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ShowSnapshot extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.images.view')
            );
    }

    public function inBanner(Banner $banner, Snapshot $snapshot): Snapshot
    {
        return $this->handle($banner, $snapshot);
    }

    public function inWebsite(PortfolioWebsite $portfolioWebsite, Snapshot $snapshot): Snapshot
    {
        return $this->handle($portfolioWebsite, $snapshot);
    }

    public function handle(PortfolioWebsite|Banner $parent, Snapshot $snapshot): Snapshot
    {
        return $snapshot;
    }

    public function htmlResponse(Snapshot $snapshot, ActionRequest $request): Response
    {
        return Inertia::render(
            'Tenant/Portfolio/StockImages',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'    => __('images'),
                'pageHead' => [
                    'title'     => __('images'),
                    'iconRight' => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-images'
                    ],
                ],
                'data' => new SnapshotResource($snapshot)
            ]
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
                        'label' => __('images'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'portfolio.images.index' =>
            array_merge(
                ShowPortfolioDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'portfolio.images.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
