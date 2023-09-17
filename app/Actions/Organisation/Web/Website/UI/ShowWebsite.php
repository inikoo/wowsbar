<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 16:47:20 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\Web\HasWorkshopAction;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Organisation\WebsiteTabsEnum;
use App\Http\Resources\Web\WebsiteResource;
use App\Models\Organisation\Web\Website;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowWebsite extends InertiaAction
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


    public function asController(Website $website, ActionRequest $request): Website
    {
        $this->initialisation($request)->withTab(WebsiteTabsEnum::values());

        return $website;
    }


    public function htmlResponse(Website $website, ActionRequest $request): Response|RedirectResponse
    {



        return Inertia::render(
            'Web/Website',
            [
                'breadcrumbs' => $this->getBreadcrumbs($request->route()->originalParameters()),
                'title'       => __('website'),
                'pageHead'    => [
                    'title'   => __('website'),
                    'icon'    => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions' => $this->workshopActions($request),
                ],

                'tabs'                           => [
                    'current'    => $this->tab,
                    'navigation' => WebsiteTabsEnum::navigation()
                ],

                // Showcase data
                WebsiteTabsEnum::SHOWCASE->value => $this->tab == WebsiteTabsEnum::SHOWCASE->value ?
                    fn () => WebsiteResource::make($website)->getArray()
                    : Inertia::lazy(fn () => WebsiteResource::make($website)->getArray())

                /*
                WebsiteTabsEnum::CHANGELOG->value => $this->tab == WebsiteTabsEnum::CHANGELOG->value ?
                    fn() => HistoryResource::collection(IndexHistories::run($website))
                    : Inertia::lazy(fn() => HistoryResource::collection(IndexHistories::run($website)))
                */


            ]
        );
    }

    public function getBreadcrumbs(array $routeParameters, $suffix = null): array
    {
        return
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type'           => 'modelWithIndex',
                        'modelWithIndex' => [
                            'index' => [
                                'route' => [
                                    'name' => 'org.websites.index'
                                ],
                                'label' => __('websites'),
                                'icon'  => 'fal fa-bars'
                            ],
                            'model' => [
                                'route' => [
                                    'name'       => 'org.websites.show',
                                    'parameters' => [$routeParameters['website']]
                                ],
                                'label' => $routeParameters['website'],
                                'icon'  => 'fal fa-bars'
                            ]


                        ],
                        'suffix'         => $suffix,
                    ]
                ]
            );
    }

}
