<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 23:50:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\Web\HasWorkshopAction;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use App\Actions\UI\WithInertia;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Enums\UI\Organisation\WebpageTabsEnum;
use App\Http\Resources\Web\WebpageResource;
use App\Models\Organisation\Web\Webpage;
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
        $actions = $this->workshopActions($request);

        if ($webpage->type == WebpageTypeEnum::BLOG) {
            $actions = array_merge(
                $actions,
                [
                    $this->canEdit ? [
                        'type'  => 'button',
                        'style' => 'create',
                        'label' => __('new article'),
                        'route' => [
                            'name'       => 'org.website.blog.article.create',
                        ]
                    ] : false
                ]
            );
        }


        return Inertia::render(
            'Web/Webpage',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->parameters()
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
                    fn() => WebpageResource::make($webpage)->getArray()
                    : Inertia::lazy(fn() => WebpageResource::make($webpage)->getArray())

                /*
                WebpageTabsEnum::CHANGELOG->value => $this->tab == WebpageTabsEnum::CHANGELOG->value ?
                    fn() => HistoryResource::collection(IndexHistories::run($webpage))
                    : Inertia::lazy(fn() => HistoryResource::collection(IndexHistories::run($webpage)))
                */


            ]
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
