<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 14 Sep 2023 20:22:41 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage\UI;

use App\Actions\InertiaAction;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateArticle extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('websites.edit');
    }


    public function asController(Website $website, ActionRequest $request): Webpage
    {
        $this->initialisation($request);

        return $website->webpages->where('type', WebpageTypeEnum::BLOG)->firstOrFail();
    }


    public function htmlResponse(Webpage $blog, ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $blog->slug,
                    $request->route()->originalParameters()
                ),
                'title'       => __('new article'),
                'pageHead'    => [
                    'title'   => __('new article'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' => [
                                'name'       => 'org.websites.show.webpages.show',
                                'parameters' => [
                                    $blog->website->slug,
                                    $blog->slug
                                ]
                            ]

                        ]
                    ]


                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('Id'),
                            'icon'   => ['fal', 'fa-fingerprint'],
                            'fields' => [


                                'url' => [
                                    'type'      => 'inputWithAddOn',
                                    'label'     => __('url'),
                                    'leftAddOn' => [
                                        'label' => 'https://'.$blog->website->domain.'/blog/'
                                    ],
                                    'value'     => '',
                                    'required'  => true,
                                ],
                            ]
                        ],
                        [
                            'title'  => __('Header'),
                            'icon'   => ['fal', 'fa-indent'],
                            'fields' => [


                                'title' => [
                                    'type'     => 'input',
                                    'label'    => __('title'),
                                    'value'    => '',
                                    'required' => true,
                                ],

                                'subtitle' => [
                                    'type'     => 'input',
                                    'label'    => __('subtitle'),
                                    'value'    => '',
                                    'required' => true,
                                ],
                            ]
                        ]


                    ],
                    'route'     => [
                        'name'      => 'org.models.article.store',
                        'arguments' => [$blog->id]
                    ],


                ],

            ]
        );
    }


    public function getBreadcrumbs(string $webpageSlug, $routeParameters): array
    {
        $routeParameters['webpage'] = $webpageSlug;

        return array_merge(
            ShowWebpage::make()->getBreadcrumbs(
                $routeParameters
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("article"),
                    ]
                ]
            ]
        );
    }


}
