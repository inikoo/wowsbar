<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 26 Jul 2023 01:43:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class RemoveBanner extends InertiaAction
{
    public function handle(ContentBlock $banner): ContentBlock
    {
        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("banners.edit");
    }

    public function inTenant(ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request);

        return $this->handle($banner);
    }

    public function inWebsite(Website $website, ContentBlock $banner, ActionRequest $request): ContentBlock
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

    public function htmlResponse(ContentBlock $banner, ActionRequest $request): Response
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
                            'website' => $request->route()->originalParameters()['website'],
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
