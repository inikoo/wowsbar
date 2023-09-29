<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerWebsite\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithPortfolioWebsiteFields;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateCustomerWebsite extends InertiaAction
{
    use WithPortfolioWebsiteFields;

    public function handle(Customer $customer): Customer
    {
        return $customer;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()>hasPermissionTo('crm.edit');
    }


    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, ActionRequest $request): Customer
    {
        $this->initialisation($request);
        return $customer;
    }


    public function htmlResponse(Customer $customer, ActionRequest $request): Response
    {

        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('new website'),
                'pageHead'    => [
                    'title'        => __('website'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]


                ],
                'formData'    => [
                    'blueprint' => $this->getPortfolioWebsiteFields(),
                    'route'     => [
                        'name'       => 'org.models.customer.customer-website.store',
                         'parameters'=> $customer->id
                    ],


                ],

            ]
        );
    }


    public function getBreadcrumbs($routeName, $routeParameters): array
    {


        return array_merge(
            IndexCustomerWebsites::make()->getBreadcrumbs(
                'org.crm.shop.customers.show.customer-websites.index',
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
        );
    }


}
