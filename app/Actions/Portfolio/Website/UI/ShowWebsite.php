<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 29 May 2023 12:18:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Website\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\ContentBlock\Banners\UI\IndexBanners;
use App\Actions\Portfolio\ContentBlock\UI\IndexContentBlocks;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolioDashboard;
use App\Actions\UI\WithInertia;
use App\Enums\UI\WebsiteTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Http\Resources\Portfolio\WebsiteResource;
use App\Models\Portfolio\Website;
use App\Models\Web\WebBlockType;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowWebsite extends InertiaAction
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('portfolio.edit');
        $this->canDelete = $request->user()->can('portfolio.edit');

        return $request->user()->can('portfolio.view');
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $this->initialisation($request)->withTab(WebsiteTabsEnum::values());

        return $website;
    }


    public function htmlResponse(Website $website, ActionRequest $request): Response
    {
        return Inertia::render(
            'Tenant/Portfolio/Website',
            [
                'title'       => __('Website'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($website, $request),
                    'next'     => $this->getNext($website, $request),
                ],
                'pageHead'    => [
                    'title'   => $website->name,
                    'icon'    => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('new banner'),
                            'route' => [
                                'name'       => $request->route()->getName().'.banners.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : false,

                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('edit'),
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : false,

                        $this->canDelete ? [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => 'portfolio.websites.remove',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : false
                    ],

                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => WebsiteTabsEnum::navigation()
                ],

                WebsiteTabsEnum::BANNERS->value => $this->tab == WebsiteTabsEnum::BANNERS->value
                    ?
                    fn () => ContentBlockResource::collection(
                        IndexBanners::run(
                            parent: $website,
                            prefix: 'banners'
                        )
                    )
                    : Inertia::lazy(fn () => ContentBlockResource::collection(
                        IndexBanners::run(
                            parent: $website,
                            prefix: 'banners'
                        )
                    )),


                WebsiteTabsEnum::CHANGELOG->value => $this->tab == WebsiteTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($website))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($website)))
            ]
        )->table(IndexHistories::make()->tableStructure())
            ->table(
                IndexContentBlocks::make()->tableStructure(
                    parent: $website,
                    modelOperations: [
                        'createLink' => $this->canEdit ? [
                            'route' => [
                                'name'       => 'portfolio.websites.show.banners.create',
                                'parameters' => array_values([$website->slug])
                            ],
                            'label' => __('banner'),
                            'style' => 'primary',
                            'icon'  => 'fas fa-plus'
                        ] : false
                    ],
                    prefix: 'banners',
                    webBlockType: WebBlockType::where('slug', 'banner')->first(),
                    canEdit: $this->canEdit
                )
            );
    }


    public function jsonResponse(Website $website): WebsiteResource
    {
        return new WebsiteResource($website);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (string $type, Website $website, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('websites')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $website->name,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $website->name
                    ],
                    'suffix' => $suffix
                ],
            ];
        };


        return match ($routeName) {
            'portfolio.websites.show',
            'portfolio.websites.edit' =>

            array_merge(
                ShowPortfolioDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['website'],
                    [
                        'index' => [
                            'name'       => 'portfolio.websites.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'portfolio.websites.show',
                            'parameters' => [$routeParameters['website']->slug]
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(Website $website, ActionRequest $request): ?array
    {
        $previous = Website::where('code', '<', $website->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Website $website, ActionRequest $request): ?array
    {
        $next = Website::where('code', '>', $website->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Website $website, string $routeName): ?array
    {
        if (!$website) {
            return null;
        }

        return match ($routeName) {
            'portfolio.websites.show' => [
                'label' => $website->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'website' => $website->slug
                    ]
                ]
            ]
        };
    }
}
