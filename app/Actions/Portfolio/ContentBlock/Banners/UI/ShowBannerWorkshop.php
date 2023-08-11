<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 14 Jul 2023 14:24:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBannerWorkshop extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('portfolio.edit');
        $this->canDelete = $request->user()->can('portfolio.edit');

        return $request->user()->hasPermissionTo("portfolio.view");
    }

    public function inTenant(ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request);

        return $banner;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWebsite(Website $website, ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request);

        return $banner;
    }


    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function htmlResponse(ContentBlock $banner, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/BannerWorkshop',
            [
                'title'             => __("Banner's workshop"),
                'breadcrumbs'       => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'        => [
                    'previous' => $this->getPrevious($banner, $request),
                    'next'     => $this->getNext($banner, $request),
                ],
                'pageHead'          => [

                    'title'     => __('Workshop'),
                    'container' => [
                        'icon'    => ['fal', 'fa-window-maximize'],
                        'tooltip' => __('Banner'),
                        'label'   => Str::possessive($banner->name)
                    ],
                    'iconRight' =>
                        [
                            'icon'  => ['fal', 'drafting-compass'],
                            'title' => __("Banner's workshop")
                        ],

                    'actionActualMethod' => 'patch',
                    'actions'            => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit workshop'),
                            'route' => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($this->originalParameters),
                            ]
                        ],
                        [
                            'type'   => 'button',
                            'style'  => 'save',
                            'route'  => [
                                'name'       => 'models.content-block.update',
                                'parameters' => [
                                    'contentBlock' => $banner->slug
                                ]
                            ],
                            'method' => 'post',

                        ]
                    ],
                ],
                'firebase'          => false,
                'bannerLayout'      => $banner->compiledLayout(),
                'imagesUploadRoute' => [
                    'name'      => $request->route()->getName().'.images.store',
                    'arguments' => $request->route()->originalParameters()
                ],
                'fcf'=>File::get(base_path(config('firebase.credentials.file')))
            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowBanner::make()->getBreadcrumbs(
            preg_replace('/workshop$/', 'show', $routeName),
            $routeParameters,
            '('.__('Workshop').')'
        );
    }

    public function getPrevious(ContentBlock $banner, ActionRequest $request): ?array
    {
        $previous = ContentBlock::where('code', '<', $banner->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName(), $request->route()->parameters);
    }

    public function getNext(ContentBlock $banner, ActionRequest $request): ?array
    {
        $next = ContentBlock::where('code', '>', $banner->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName(), $request->route()->parameters);
    }

    private function getNavigation(?ContentBlock $banner, string $routeName, array $routeParameters): ?array
    {
        if (!$banner) {
            return null;
        }

        return match ($routeName) {
            'portfolio.banners.workshop', 'portfolio.websites.show.banners.workshop' => [
                'label' => $banner->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => $routeParameters
                ]
            ],
        };
    }

}
