<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Thu, 25 Apr 2024 16:56:22 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Web\Website\UI\GetWebsiteWorkshopFooter;
use App\Models\Market\Shop;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowFooter
{
    use AsAction;


    private Website $website;

    private Webpage|Website $parent;

    private Shop $scope;

    public bool $asAction = false;

    public function handle(Website $website): Website
    {
        return $website;
    }

    public function htmlResponse(Website $website, ActionRequest $request): Response
    {
        return Inertia::render(
            'Banners/FooterWorkshop',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('footer'),
                'pageHead'    => [
                    'title'    => $website->code,
                    'icon'     => [
                        'title' => __('footer'),
                        'icon'  => 'fal fa-browser'
                    ],
                    'actions'            => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit workshop'),
                            'route' => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters()),
                            ]
                        ],
                        /* [
                            'type'  => 'button',
                            'style' => 'primary',
                            'icon'  => ["fas", "fa-rocket"],
                            'label' => __('Publish'),
                            'route' => [
                                'method'     => 'post',
                                'name'       => 'grp.models.website.publish.footer',
                                'parameters' => [
                                    'website' => $website->id
                                ],
                            ]
                        ], */
                    ],
                ],

                'uploadImageRoute' => [
                    'name'       => 'grp.models.website.footer.images.store',
                    'parameters' => [
                        'website' => $website->id
                    ]
                ],

                'autosaveRoute' => [
                    'name'       => 'grp.models.website.autosave.footer',
                    'parameters' => [
                        'website' => $website->id
                    ]
                ],

                'data' => GetWebsiteWorkshopFooter::run($website),
            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return true;
    }

    public function asController(ActionRequest $request): Website
    {
        $this->parent = $request->get('website');

        return $this->parent;
    }

    public function getBreadcrumbs($routeName, $routeParameters): array
    {
        return [];
    }

}
