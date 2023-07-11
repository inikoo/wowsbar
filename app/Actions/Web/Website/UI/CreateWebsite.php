<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Thu, 18 May 2023 14:27:30 Central European Summer, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Actions\Web\Website\UI;

use App\Actions\InertiaAction;
use App\Actions\Market\Shop\UI\ShowShop;
use App\Models\Market\Shop;
use App\Models\Tenancy\Tenant;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateWebsite extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('shops.edit');
    }


    /**
     * @throws \Exception
     */
    public function inTenant(ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle(app('currentTenant'), $request);
    }

    /**
     * @throws Exception
     */
    public function inShop(Shop $shop, ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);
        if ($shop->website) {
            return Redirect::route('web.websites.show', [
                $shop->website->slug
            ]);
        }

        return $this->handle($shop, $request);
    }

    /**
     * @throws Exception
     */
    public function handle(Tenant|Shop $parent, ActionRequest $request): Response
    {
        $scope     = $parent;
        $container = null;
        if (class_basename($scope) == 'Shop') {
            $container = [
                'icon'    => ['fal', 'fa-store-alt'],
                'tooltip' => __('Shop'),
                'label'   => Str::possessive($scope->name)
            ];
        }

        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $scope,
                    routeParameters: $request->route()->parameters
                ),
                'title'       => __('new website'),
                'pageHead'    => [
                    'title'        => __('website'),
                    'container'    => $container,
                    'cancelCreate' => [
                        'route' => [
                            'name'       => 'shops.show',
                            'parameters' => array_values($this->originalParameters)
                        ],
                    ]

                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('domain'),
                            'fields' => [

                                'domain' => [
                                    'type'      => 'inputWithAddOn',
                                    'label'     => __('domain'),
                                    'leftAddOn' => [
                                        'label' => 'http://www.'
                                    ],
                                    'required'  => true,
                                ],


                            ]
                        ],
                        [
                            'title'  => __('ID/name'),
                            'fields' => [

                                'code' => [
                                    'type'     => 'input',
                                    'label'    => __('code'),
                                    'required' => true,
                                ],
                                'name' => [
                                    'type'     => 'input',
                                    'label'    => __('name'),
                                    'required' => true,
                                    'value'    => '',
                                ],


                            ]
                        ],


                    ],
                    'route'     =>
                        match (class_basename($scope)) {
                            'Shop' => [
                                'name'      => 'models.shop.website.store',
                                'arguments' => [$parent->slug]
                            ],
                            'Tenant' => [
                                'name' => 'models.website.store',
                            ],
                        }


                ],

            ]
        );
    }


    public function getBreadcrumbs(Tenant|Shop $scope, $routeParameters): array
    {
        return match (class_basename($scope)) {
            'Shop' => array_merge(
                ShowShop::make()->getBreadcrumbs(
                    $routeParameters
                ),
                [
                    [
                        'type'          => 'creatingModel',
                        'creatingModel' => [
                            'label' => __("creating website"),
                        ]
                    ]
                ]
            ),
            'Tenant' => array_merge(
                IndexWebsites::make()->getBreadcrumbs(
                    'web.websites.index',
                    []
                ),
                [
                    [
                        'type'          => 'creatingModel',
                        'creatingModel' => [
                            'label' => __("creating website"),
                        ]
                    ]
                ]
            ),
        };
    }


}
