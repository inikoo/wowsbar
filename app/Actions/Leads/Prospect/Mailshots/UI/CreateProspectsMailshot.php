<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Mailshots\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspectQueries;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateProspectsMailshot extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.prospects.edit');
    }

    public function asController(ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle(organisation(), $request);
    }

    public function inShop(Shop $shop, ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle($shop, $request);
    }


    public function handle(Organisation|Shop $parent, ActionRequest $request): Response
    {
        $fields[] = [
            'title'  => '',
            'fields' => [
                'subject' => [
                    'type'        => 'input',
                    'label'       => __('subject'),
                    'placeholder' => __('Email subject'),
                    'required'    => true,
                    'value'       => '',
                ],
            ]
        ];

        $fields[] = [
            'title'  => '',
            'fields' => [
                'subject' => [
                    'type'        => 'prospectsQueries',
                    'label'       => __('prospects'),
                    'placeholder' => __('Email subject'),
                    'required'    => true,
                    'options'     => IndexProspectQueries::run(),
                ],
            ]
        ];


        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->originalParameters()
                ),
                'title'       => __('new mailshot'),
                'pageHead'    => [
                    'title'   => __('Prospect mailshot'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' => $fields,
                    'route'     =>
                        match (class_basename($parent)) {
                            'Shop' => [
                                'name'       => 'org.models.shop.prospect-mailshot.store',
                                'parameters' => [
                                    'shop' => $parent->id,
                                ]
                            ],
                            default => [
                                'name' => 'org.models.prospect-mailshot.store',
                            ],
                        }
                ],

            ]
        );
    }


    public function getBreadcrumbs(array $routeParameters): array
    {
        return array_merge(
            IndexProspectMailshots::make()->getBreadcrumbs(
                'org.crm.shop.prospects.mailshots.index',
                $routeParameters
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating mailshot"),
                    ]
                ]
            ]
        );
    }


}