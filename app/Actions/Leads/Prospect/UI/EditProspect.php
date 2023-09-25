<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\UI;

use App\Actions\InertiaAction;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditProspect extends InertiaAction
{
    public function handle(Prospect $prospect): Prospect
    {
        return $prospect;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('crm.customers.edit');

        return $request->user()->hasPermissionTo("shops.customers.edit");
    }

    public function inShop(Shop $shop, Prospect $prospect, ActionRequest $request): Prospect
    {
        $this->initialisation($request);

        return $this->handle($prospect);
    }

    public function htmlResponse(Prospect $prospect, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                'title'       => __('prospect'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'                            => [
                    'previous' => $this->getPrevious($prospect, $request),
                    'next'     => $this->getNext($prospect, $request),
                ],
                'pageHead'    => [
                    'title'    => $prospect->name,
                    'exitEdit' => [
                        'route' => [
                            'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                            'parameters' => array_values($request->route()->originalParameters()),
                        ]
                    ],
                ],

                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('contact information'),
                            'fields' => [
                                'contact_name' => [
                                    'type'  => 'input',
                                    'label' => __('contact name'),
                                    'value' => $prospect->contact_name
                                ],
                                'company_name' => [
                                    'type'  => 'input',
                                    'label' => __('company'),
                                    'value' => $prospect->company_name
                                ],
                                'phone'        => [
                                    'type'  => 'phone',
                                    'label' => __('phone'),
                                    'value' => $prospect->phone
                                ],
                            ]
                        ]
                    ],
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.customer.update',
                            'parameters' => $prospect->slug
                        ],
                    ]

                ],

            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowProspect::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }

    public function getPrevious(Prospect $prospect, ActionRequest $request): ?array
    {

        $previous = Prospect::where('slug', '<', $prospect->slug)->when(true, function ($query) use ($prospect, $request) {
            if ($request->route()->getName() == 'org.shops.show.customers.show') {
                $query->where('customers.shop_id', $prospect->shop_id);
            }
        })->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());

    }

    public function getNext(Prospect $prospect, ActionRequest $request): ?array
    {
        $next = Prospect::where('slug', '>', $prospect->slug)->when(true, function ($query) use ($prospect, $request) {
            if ($request->route()->getName() == 'org.shops.show.customers.show') {
                $query->where('customers.shop_id', $prospect->shop_id);
            }
        })->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Prospect $prospect, string $routeName): ?array
    {
        if(!$prospect) {
            return null;
        }

        return match ($routeName) {
            'org.crm.shop.prospects.edit' => [
                'label'=> $prospect->name,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> $this->originalParameters
                ]
            ]
        };
    }
}
