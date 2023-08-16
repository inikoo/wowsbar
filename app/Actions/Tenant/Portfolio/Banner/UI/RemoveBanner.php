<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:17:02 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class RemoveBanner extends InertiaAction
{
    public function handle(Banner $banner): Banner
    {
        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("banners.edit");
    }

    public function inTenant(Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request);

        return $this->handle($banner);
    }

    public function inWebsite(PortfolioWebsite $website, Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request);

        return $this->handle($banner);
    }


    public function getAction($route): array
    {
        return [
            'buttonLabel' => __('Delete'),
            'title'       => __('Delete banner'),
            'text'        => __("This action will delete this banner"),
            'route'       => $route
        ];
    }

    public function htmlResponse(Banner $banner, ActionRequest $request): Response
    {
        return Inertia::render(
            'RemoveModel',
            [
                'title'       => __('delete banner'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'pageHead'    => [
                    'icon'    =>
                        [
                            'icon'  => 'fal fa-window-maximize',
                            'title' => __('banner')
                        ],
                    'title'   => $banner->slug,
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => preg_replace('/remove$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ]
                ],
                'data'        => $this->getAction(
                    route: [
                        'name'       => 'models.content-block.delete',
                        'parameters' => [
                            'contentBlock' => $request->route()->originalParameters()['banner']
                        ]
                    ]
                )


            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowBanner::make()->getBreadcrumbs(
            routeName: preg_replace('/remove$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('deleting').')'
        );
    }
}
