<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product\UI;

use App\Actions\InertiaAction;
use App\Models\Market\ShopProduct;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class RemoveProduct extends InertiaAction
{
    public function handle(ShopProduct $product): ShopProduct
    {
        return $product;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.edit");
    }

    public function inTenant(ShopProduct $product, ActionRequest $request): ShopProduct
    {
        $this->initialisation($request);

        return $this->handle($product);
    }



    /** @noinspection PhpUnusedParameterInspection */
    public function inShop(Shop $shop, ShopProduct $product, ActionRequest $request): ShopProduct
    {
        $this->initialisation($request);

        return $this->handle($product);
    }


    public function getAction($route): array
    {
        return  [
            'buttonLabel' => __('Delete'),
            'title'       => __('Delete Product'),
            'text'        => __("This action will delete this Product and all it's dependent"),
            'route'       => $route
        ];
    }

    public function htmlResponse(ShopProduct $product, ActionRequest $request): Response
    {

        return Inertia::render(
            'RemoveModel',
            [
                'title'       => __('delete product'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'pageHead'    => [
                    'icon'  =>
                        [
                            'icon'  => ['fal', 'fa-cube'],
                            'title' => __('product')
                        ],
                    'title'  => $product->slug,
                    'actions'=> [
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
                'data'     => $this->getAction(
                    route:
                    match ($request->route()->getName()) {
                        'shops.products.remove' => [
                            'name'       => 'models.product.delete',
                            'parameters' => $request->route()->originalParameters()
                        ],
                        'shops.show.products.remove' => [
                            'name'       => 'models.shop.product.delete',
                            'parameters' => $request->route()->originalParameters()
                        ]
                    }
                )




            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowProduct::make()->getBreadcrumbs(
            routeName: preg_replace('/remove$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('deleting').')'
        );
    }
}
