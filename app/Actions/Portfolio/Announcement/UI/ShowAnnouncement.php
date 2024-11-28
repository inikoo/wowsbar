<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Announcement\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\WelcomeWidgets\WithFirstBanner;
use App\Enums\Helpers\Snapshot\SnapshotStateEnum;
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

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, Announcement $announcement, ActionRequest $request): Announcement
    {
        $this->initialisation($request);
        $this->parent = $portfolioWebsite;

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

        // $resetRoute = [
        //     'reset_route' => [
        //         'name'       => 'customer.models.portfolio-website.announcement.reset',
        //         'parameters' => [
        //             'portfolioWebsite' => $announcement->portfolio_website_id,
        //             'announcement'     => $announcement->id
        //         ]
        //     ],
        // ];

        return Inertia::render(
            'Banners/Announcement',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'    => __('Announcement'),
                'pageHead' => [
                    'model'     => __('Announcement'),
                    'title'     => $announcement->name,
                    'container' => $container,
                    'icon'      => [
                        'icon' => 'fal fa-sign'
                    ],
                    'iconRight' => [
                        'icon' => 'fal fa-seedling'
                    ],
                ],
                'routes_list' => [
                    'publish_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.publish',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ]
                    ],
                    'update_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.update',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ]
                    ],
                    'reset_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.reset',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ]
                    ]
                ],
                'is_announcement_dirty'     => $announcement->is_dirty,
                'portfolio_website'         => $announcement->portfolioWebsite,
                // 'firstBanner'             => $this->canEdit ? $this->getFirstBannerWidget($scope) : null,
                'announcement_data'       => $announcement->toArray(),
                // 'announcement_list'       => [],
                'is_announcement_published' => $announcement->unpublishedSnapshot->state === SnapshotStateEnum::LIVE,  // TODO
                'is_announcement_active'    => $announcement->unpublishedSnapshot->state === SnapshotStateEnum::LIVE,  // TODO
                // 'route_toggle_activated'    => [   // TODO
                //     'name'  => 'customer.models.banner.announcement.toggle',
                //     'parameters'    => [
                //         'announcement' => $announcement->id
                //     ]
                // ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating announcement"),
                    ]
                ]
            ];
        };

        return match ($routeName) {
            'customer.portfolio.websites.announcements.show' =>
            array_merge(
                IndexAnnouncement::make()->getBreadcrumbs($routeName, $routeParameters),
                $headCrumb(
                    [
                        'name'       => 'customer.portfolio.websites.announcements.show',
                        'parameters' => $routeParameters
                    ]
                ),
            ),
            default => []
        };
    }
}
