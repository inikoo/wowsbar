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
                    'iconRight' => $announcement->status->statusIcon()[$announcement->status->value],
                ],
                'routes_list' => [
                    'publish_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.publish',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ],
                        'method'    => 'patch'
                    ],
                    'update_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.update',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ],
                        'method'    => 'patch'
                    ],
                    'reset_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.reset',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ]
                    ],
                    'close_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.close',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ],
                        'method'    => 'patch'
                    ],
                    'start_route' => [
                        'name'       => 'customer.models.portfolio-website.announcement.start',
                        'parameters' => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ],
                        'method'    => 'patch'
                    ],
                    'activated_route'     => [
                        'name'          => 'customer.models.portfolio-website.announcement.toggle',
                        'parameters'    => [
                            'portfolioWebsite' => $announcement->portfolio_website_id,
                            'announcement'     => $announcement->id
                        ],
                        'method'    => 'patch'
                    ],
                    'upload_image_route'     => [
                        'name'          => 'customer.models.portfolio-website.announcement.upload-images.store',
                        'parameters'    => [
                            'portfolioWebsite' => $announcement->portfolio_website_id
                        ],
                        'method'    => 'post'
                    ],
                    'delete_announcement_route'     => [
                        'name'          => 'customer.models.portfolio-website.announcement.delete',
                        'parameters'    => [
                            'portfolioWebsite' => $announcement->portfolio_website_id
                        ],
                        'method'    => 'delete'
                    ]
                ],
                'is_announcement_dirty'       => $announcement->is_dirty,
                'is_announcement_started'     => $this->isAnnouncementStarted($announcement),
                'is_announcement_closed'      => $this->isAnnouncementClosed($announcement),
                'portfolio_website'           => $announcement->portfolioWebsite,
                'announcement_data'           => $announcement->toArray(),
                'is_announcement_published'   => $announcement->unpublishedSnapshot->state === SnapshotStateEnum::LIVE,  // TODO
                'is_announcement_active'      => $announcement->status,
                'last_published_date'         => $announcement->ready_at,
            ]
        );
    }

    /**
     * Determine if the announcement has started.
     */
    public function isAnnouncementStarted($announcement): bool
    {
        if (! $announcement->live_at) {
            return false;
        }

        if (!$announcement->schedule_finish_at) {
            return true;
        }

        if ($announcement->live_at->lessThan(now()) && !$announcement->schedule_at) {
            return true;
        }

        return $announcement->live_at->lessThan(now()) || now()->between($announcement->schedule_finish_at, $announcement->schedule_at);
    }

    /**
     * Determine if the announcement is closed.
     */
    public function isAnnouncementClosed($announcement): bool
    {
        if (! $announcement->live_at) {
            return true;
        }

        if (!$announcement->closed_at) {
            return false;
        }

        return !$announcement->live_at->lessThan(now()) || now()->isAfter($announcement->closed_at);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (Announcement $announcement, array $routeParameters, string $suffix = '') {
            return [
                [
                    'type'           => 'simple',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('customer websites')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $announcement->name,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $announcement->name
                    ],
                    'suffix'         => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'customer.portfolio.websites.announcements.show' =>
            array_merge(
                IndexAnnouncement::make()->getBreadcrumbs($routeName, [
                    'portfolioWebsite' => $routeParameters['portfolioWebsite']
                ]),
                $headCrumb(
                    $routeParameters['announcement'],
                    [
                        'index' => [
                            'name'       => 'customer.portfolio.websites.announcements.index',
                            'parameters' => $routeParameters['portfolioWebsite']
                        ],
                        'model' => [
                            'name'       => 'customer.portfolio.websites.announcements.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                ),
            ),
            default => []
        };
    }
}
