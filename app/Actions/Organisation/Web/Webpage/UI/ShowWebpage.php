<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 23:50:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Organisation\WebpageTabsEnum;
use App\Http\Resources\Web\WebsiteResource;
use App\Models\Auth\User;
use App\Models\Organisation\Web\Webpage;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowWebpage extends InertiaAction
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('website.edit');
        $this->canDelete = $request->user()->can('website.edit');
        return $request->user()->hasPermissionTo("website.view");
    }



    public function asController(Webpage $webpage, ActionRequest $request): Webpage
    {
        $this->initialisation($request)->withTab(WebpageTabsEnum::values());

        return $webpage;
    }


    public function htmlResponse(Webpage $webpage, ActionRequest $request): Response
    {



        return Inertia::render(
            'Web/Webpage',
            [
                'breadcrumbs'                    => $this->getBreadcrumbs(
                    $request->route()->parameters()),
                'title'                          => __('webpage'),
                'pageHead'                       => [
                    'title' => __('webpage'),
                    'icon'  => [
                        'title' => __('webpage'),
                        'icon'  => 'fal fa-browser'
                    ],
                    'actions'                    => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('settings'),
                            'icon'  => ["fal", "fa-sliders-h"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('workshop'),
                            'icon'  => ["fal", "fa-drafting-compass"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'workshop', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,

                    ],
                ],

                'tabs'                           => [
                    'current'    => $this->tab,
                    'navigation' => WebpageTabsEnum::navigation()
                ],

                // Showcase data
                WebpageTabsEnum::SHOWCASE->value => $this->tab == WebpageTabsEnum::SHOWCASE->value ?
                fn () => WebsiteResource::make($webpage)->getArray()
                : Inertia::lazy(fn () => WebsiteResource::make($webpage)->getArray())

                /*
                WebpageTabsEnum::CHANGELOG->value => $this->tab == WebpageTabsEnum::CHANGELOG->value ?
                    fn() => HistoryResource::collection(IndexHistories::run($webpage))
                    : Inertia::lazy(fn() => HistoryResource::collection(IndexHistories::run($webpage)))
                */


            ]
        );
    }

    public function getBreadcrumbs($routeParameters, string $suffix = ''): array
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
                    'suffix' => $suffix

                ],
            ];
        };

        return  array_merge(
            ShowWebsite::make()->getBreadcrumbs(),
            $headCrumb(
                $routeParameters['webpage'],
                [
                    'index' => [
                        'name'       => 'org.website.webpages.index',
                        'parameters' => []
                    ],
                    'model' => [
                        'name'       => 'org.website.webpages.show',
                        'parameters' => [$routeParameters['webpage']->slug]
                    ]
                ],
                $suffix
            ),
        );

    }
}
