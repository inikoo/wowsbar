<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 00:45:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Queries\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Models\Helpers\Query;
use App\Models\Market\Shop;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditProspectQuery extends InertiaAction
{
    public function handle(Shop $parent, Query $query, ActionRequest $request): Response
    {
        $filter = Arr::get($query->constrains, 'filter', []);
        $tags = Arr::get($filter, array_key_first($filter), []);

        return Inertia::render(
            'EditModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('edit prospect list'),
                'pageHead'    => [
                    'title'   => __('edit prospect list'),
                    'icon'    => [
                        'icon'  => ['fal', 'fa-code-branch'],
                        'title' => __('prospect list')
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' =>$request->route()->originalParameters()
                            ],
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' =>
                         [
                             'properties' => [
                                'label'  => __('properties'),
                                'fields' => [
                                    'name'          => [
                                        'type'  => 'input',
                                        'label' => __('name'),
                                        'value' => $query->name
                                    ],
                                    'query_builder' => [
                                        'type'  => 'prospect_query',
                                        'label' => __('query by'),
                                        'value' => [
                                            'query' => (array) Arr::get($query->constrains, 'with', []),
                                            'tag'  => [
                                                'state' => array_key_first($filter),
                                                'tags' => $tags
                                            ],
                                            'last_contact' => [
                                                'state' => (bool) Arr::get($query->arguments, '__date__'),
                                                'data' => [
                                                    'unit' => Arr::get($query->arguments, '__date__')['value']['unit'],
                                                    'quantity' => Arr::get($query->arguments, '__date__')['value']['quantity']
                                                ]
                                            ],
                                        ]
                                    ],


                                ]
                            ],
                        ],
                    'args'      => [
                        'updateRoute'     =>
                            [
                                'name'       => 'org.models.shop.prospect-query.update',
                                'parameters' => [$parent->id, $query->slug]
                            ]
                    ]
                ]
            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.edit');
    }

    public function inShop(Shop $shop, Query $query, ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle($shop, $query, $request);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexProspects::make()->getBreadcrumbs(
                routeName: 'org.crm.shop.prospects.index',
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('editing prospect list'),
                    ]
                ]
            ]
        );
    }
}
