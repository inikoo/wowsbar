<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Subscriptions\CustomerWebsite\UI;

use App\Actions\InertiaAction;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Portfolios\CustomerWebsite;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditCustomerWebsite extends InertiaAction
{
    public function handle(CustomerWebsite $customerWebsite): CustomerWebsite
    {
        return $customerWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');
        return $request->user()->hasPermissionTo("crm.edit");

    }

    public function inShop(Shop $shop, Customer $customer, CustomerWebsite $customerWebsite, ActionRequest $request): CustomerWebsite
    {
        $this->initialisation($request);

        return $this->handle($customerWebsite);
    }

    public function inCustomerInShop(Shop $shop, Customer $customer, CustomerWebsite $customerWebsite, ActionRequest $request): CustomerWebsite
    {
        $this->initialisation($request);

        return $this->handle($customerWebsite);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(CustomerWebsite $customerWebsite, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                'title'       => __("CustomerWebsite's settings"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $this->originalParameters
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($customerWebsite, $request),
                    'next'     => $this->getNext($customerWebsite, $request),
                ],
                'pageHead' => [
                    'title'     => __('Edit website'),
                    'container' => [
                        'icon'    => ['fal', 'fa-globe'],
                        'tooltip' => __('CustomerWebsite'),
                        'label'   => Str::possessive($customerWebsite->name)
                    ],

                    'iconRight' =>
                        [
                            'icon'  => ['fal', 'fa-edit'],
                            'title' => __("Editing website")
                        ],

                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit edit'),
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ],
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('ID/domain'),
                            'icon'   => 'fa-light fa-id-card',
                            'fields' => [
                                'name' => [
                                    'type'     => 'input',
                                    'label'    => __('name'),
                                    'value'    => $customerWebsite->name,
                                    'required' => true,
                                ],
                                'url' => [
                                    'type'      => 'inputWithAddOn',
                                    'label'     => __('domain'),
                                    'leftAddOn' => [
                                        'label' => 'https://'
                                    ],
                                    'value'    => $customerWebsite->url,
                                    'required' => true,
                                ],
                            ]
                        ],
                        [
                            'title'  => __('Google Services'),
                            'icon'   => 'fab fa-google',
                            'fields' => [
                                'property_id' => [
                                    'type'     => 'input',
                                    'label'    => __('analytics property id'),
                                    'value'    => Arr::get($customerWebsite->data, 'property_id'),
                                    'required' => true,
                                ],
                                'google_ads_id' => [
                                    'type'     => 'input',
                                    'label'    => __('google ads id'),
                                    'value'    => Arr::get($customerWebsite->data, 'google_ads_id'),
                                    'required' => true,
                                ],
                            ]
                        ],

                    ],
                    'args' => [
                        'updateRoute' => [
                            'name'       => 'org.models.customer-website.update',
                            'parameters' => [
                                'customerWebsite' => $request->route()->originalParameter('customerWebsite')
                            ]
                        ],
                    ]
                ],

            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowCustomerWebsite::make()->getBreadcrumbs(
            $routeName,
            $routeParameters,
            suffix: '(' . __('editing') . ')'
        );
    }

    public function getPrevious(CustomerWebsite $customerWebsite, ActionRequest $request): ?array
    {
        $previous = CustomerWebsite::where('id', '<', $customerWebsite->id)->orderBy('id', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(CustomerWebsite $customerWebsite, ActionRequest $request): ?array
    {
        $next = CustomerWebsite::where('id', '>', $customerWebsite->id)->orderBy('id')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?CustomerWebsite $customerWebsite, string $routeName): ?array
    {
        if (!$customerWebsite) {
            return null;
        }

        return match ($routeName) {
            'customer.portfolio.websites.edit',
            'org.subscriptions.shop.customer-websites.edit',
            'org.crm.shop.customers.show.customer-websites.edit' => [
                'label' => $customerWebsite->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => $this->originalParameters
                ]
            ]
        };
    }
}
