<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 23:50:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\Web\HasWorkshopAction;
use App\Actions\Organisation\Web\Webpage\IndexWebpages;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use App\Actions\UI\WithInertia;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Enums\UI\Organisation\WebpageTabsEnum;
use App\Http\Resources\Web\WebpageResource;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowWebpage extends InertiaAction
{
    use AsAction;
    use WithInertia;
    use HasWorkshopAction;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('websites.edit');
        $this->canDelete = $request->user()->can('websites.edit');

        return $request->user()->hasPermissionTo("websites.view");
    }


    public function asController(Webpage $webpage, ActionRequest $request): Webpage
    {
        $this->initialisation($request)->withTab(WebpageTabsEnum::values());

        return $webpage;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWebsite(Website $website, Webpage $webpage, ActionRequest $request): Webpage
    {
        $this->initialisation($request)->withTab(WebpageTabsEnum::values());

        return $webpage;
    }


    public function htmlResponse(Webpage $webpage, ActionRequest $request): Response
    {
        $actions = $this->workshopActions($request);

        if ($webpage->purpose == WebpagePurposeEnum::BLOG) {
            $actions = array_merge(
                $actions,
                [
                    $this->canEdit ? [
                        'type'  => 'button',
                        'style' => 'create',
                        'label' => __('new article'),
                        'route' => [
                            'name'       => 'org.websites.show.blog.article.create',
                            'parameters' => [
                                'website' => $webpage->website->slug,
                            ]
                        ]
                    ] : false
                ]
            );
        } elseif ($webpage->type == WebpageTypeEnum::STOREFRONT) {
            $actions = array_merge(
                $actions,
                [
                    $this->canEdit ? [
                        'type'  => 'button',
                        'style' => 'create',
                        'label' => __('Main webpage'),
                        'route' => [
                            'name'       => 'org.websites.show.webpages.show.webpages.create',
                            'parameters' => [
                                'website' => $webpage->website->slug,
                                'webpage' => $webpage->slug

                            ]
                        ]
                    ] : false
                ]
            );
        } elseif (in_array(
            $webpage->type,
            [
                WebpageTypeEnum::SHOP,
                WebpageTypeEnum::CONTENT
            ]
        )) {
            $actions = array_merge(
                $actions,
                [
                    $this->canEdit ? [
                        'type'  => 'button',
                        'style' => 'create',
                        'label' => __('webpage'),
                        'route' => [
                            'name'       => 'org.websites.show.webpages.show.webpages.create',
                            'parameters' => [
                                'website' => $webpage->website->slug,
                                'webpage' => $webpage->slug
                            ]
                        ]
                    ] : false
                ]
            );
        }


        return Inertia::render(
            'Web/Webpage',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->originalParameters()
                ),
                'title'       => __('webpage'),
                'pageHead'    => [
                    'title'   => $webpage->code,
                    'icon'    => [
                        'title' => __('webpage'),
                        'icon'  => 'fal fa-browser'
                    ],
                    'actions' => $actions,
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => WebpageTabsEnum::navigation($webpage)
                ],

                WebpageTabsEnum::SHOWCASE->value => $this->tab == WebpageTabsEnum::SHOWCASE->value ?
                    fn () => WebpageResource::make($webpage)->getArray()
                    : Inertia::lazy(fn () => WebpageResource::make($webpage)->getArray()),


                WebpageTabsEnum::WEBPAGES->value => $this->tab == WebpageTabsEnum::WEBPAGES->value
                    ?
                    fn () => WebpageResource::collection(
                        IndexWebpages::run(
                            parent: $webpage,
                            prefix: 'webpages'
                        )
                    )
                    : Inertia::lazy(fn () => WebpageResource::collection(
                        IndexWebpages::run(
                            parent: $webpage,
                            prefix: 'webpages'
                        )
                    )),


                /*
                WebpageTabsEnum::CHANGELOG->value => $this->tab == WebpageTabsEnum::CHANGELOG->value ?
                    fn() => HistoryResource::collection(IndexHistories::run($webpage))
                    : Inertia::lazy(fn() => HistoryResource::collection(IndexHistories::run($webpage)))
                */


            ]
        )->table(
            IndexWebpages::make()->tableStructure(parent: $webpage, prefix: 'webpages')
        );
    }

    public function getBreadcrumbs(array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (Webpage $webpage, array $routeParameters, string $suffix) {
            return [
                [

                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('webpages')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $webpage->code,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };

        return array_merge(
            ShowWebsite::make()->getBreadcrumbs(
                [
                    'website'=> $routeParameters['website']
                ]
            ),
            $headCrumb(
                Webpage::where('slug',$routeParameters['webpage'])->first(),
                [
                    'index' => [
                        'name'       => 'org.websites.show.webpages.index',
                        'parameters' => [
                            'website' => $routeParameters['website']

                        ]
                    ],
                    'model' => [
                        'name'       => 'org.websites.show.webpages.show',
                        'parameters' => [
                            'website' => $routeParameters['website'],
                            'webpage' => $routeParameters['webpage']
                        ]
                    ]
                ],
                $suffix
            ),
        );
    }
}
