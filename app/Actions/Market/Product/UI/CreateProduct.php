<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Product\UI;

use App\Actions\InertiaAction;
use App\Enums\Market\Product\ProductTypeEnum;
use App\Models\Market\Shop;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreateProduct extends InertiaAction
{
    /**
     * @throws Exception
     */
    public function handle(Shop $shop, ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('new product'),
                'pageHead'    => [
                    'title'        => __('new product'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name' => match ($this->routeName) {
                                    'org.shops.show.products.create'    => 'org.shops.show.products.index',
                                    'org.shops.products.create'         => 'org.shops',
                                    default                             => preg_replace('/create$/', 'index', $this->routeName)
                                },
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' =>
                        [
                            [
                                'title'  => __('name'),
                                'fields' => [
                                    'code' => [
                                        'type'      => 'input',
                                        'label'     => __('code'),
                                        'required'  => true
                                    ],
                                    'name' => [
                                        'type'      => 'input',
                                        'label'     => __('name'),
                                        'required'  => true
                                    ],

                                    'description' => [
                                        'type'  => 'input',
                                        'label' => __('description')
                                    ],
                                    'units' => [
                                        'type'  => 'input',
                                        'label' => __('units')
                                    ],
                                    'price' => [
                                        'type'    => 'input',
                                        'label'   => __('price'),
                                        'required'=> true,
                                    ],
                                    'type' => [
                                        'type'          => 'select',
                                        'label'         => __('type'),
                                        'placeholder'   => 'Select a Product Type',
                                        'options'       => Options::forEnum(ProductTypeEnum::class)->toArray(),
                                        'required'      => true,
                                        'mode'          => 'single'
                                    ]

                                ]
                            ]
                        ],
                    'route' => match ($this->routeName) {
                        'org.shops.show.products.create' => [
                            'name'      => 'org.models.show.product.store',
                            'arguments' => [$shop->slug]
                        ],
                        default => [
                            'name' => 'org.models.product.store'
                        ]
                    }
                ]

            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('shops.products.edit');
    }

    /**
     * @throws Exception
     */
    public function asController(Shop $shop, ActionRequest $request): Response
    {
        $this->initialisation($request);
        return $this->handle($shop, $request);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexProducts::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'         => 'creatingModel',
                    'creatingModel'=> [
                        'label'=> __('creating product'),
                    ]
                ]
            ]
        );
    }
}