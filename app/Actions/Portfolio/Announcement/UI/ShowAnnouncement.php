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
use App\Models\Announcement;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowAnnouncement extends InertiaAction
{
    use WithFirstBanner;

    private Customer|PortfolioWebsite $parent;
    protected array $elementGroups = [];

    public function handle(Announcement $announcement, $prefix = null): Announcement
    {
        return $announcement;
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

    public function inCustomer(Announcement $announcement, ActionRequest $request): Announcement
    {
        $this->initialisation($request);
        $this->parent = customer();

        return $this->handle($announcement);
    }

    public function htmlResponse(Announcement $announcement, ActionRequest $request): Response
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
                'store_route' => [
                    'name'        => 'customer.models.banner.announcement.update',
                    'parameters' => [
                        'announcement' => $announcement->id
                    ]
                ],
                'firstBanner'      => $this->canEdit ? $this->getFirstBannerWidget($scope) : null,
                'announcementData' => $announcement->toArray()
            ]
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
