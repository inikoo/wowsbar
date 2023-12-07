<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:32:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Surveys\UI;

use App\Actions\InertiaAction;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateCustomerSurvey extends InertiaAction
{
    public function handle(Organisation|Shop $parent, ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('new surveys'),
                'pageHead'    => [
                    'title'   => __('new surveys'),
                    'icon'    => [
                        'icon'  => ['fal', 'fa-user'],
                        'title' => __('customer')
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => match ($request->route()->getName()) {
                                    'shops.show.customers.surveys.create' => 'org.shops.customers.surveys.index',
                                    default                               => preg_replace('/create$/', 'index', $request->route()->getName())
                                },
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' =>
                        [
                            [
                                'title'  => __('information'),
                                'fields' => [
                                    'name' => [
                                        'type'  => 'input',
                                        'label' => __('survey name')
                                    ]
                                ]
                            ],
                        ],
                    'route'     =>
                        match(class_basename($parent)) {
                            'Shop'=> [
                                'name'       => 'org.models.shop.surveys.store',
                                'parameters' => [$parent->id]
                            ],
                            default=> [
                                [
                                    'name'      => 'org.models.surveys.store',
                                ]
                            ]
                        }
                ]

            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.edit');
    }


    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);
        return $this->handle(organisation(), $request);
    }

    public function inShop(Shop $shop, ActionRequest $request): Response
    {
        $this->initialisation($request);
        return $this->handle($shop, $request);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexCustomerSurveys::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating surveys'),
                    ]
                ]
            ]
        );
    }
}
