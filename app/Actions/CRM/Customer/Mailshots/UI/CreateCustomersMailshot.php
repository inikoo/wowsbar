<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Mailshots\UI;

use App\Actions\CRM\Customer\Queries\UI\IndexCustomerQueries;
use App\Actions\InertiaAction;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateCustomersMailshot extends InertiaAction
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

        $tags = explode(',', $request->get('tags'));

        $fields[] = [
            'title'  => '',
            'fields' => [
                'recipients' => [
                    'type'        => 'prospectQueryChooser',
                    'label'       => __('recipients'),
                    'required'    => true,
                    'options'     => [
                        'query'  => IndexCustomerQueries::run(),
                        'custom' => '',
                    ],
                    'full'      => true,
                    'value'     => [
                        'recipient_builder_type' => 'query',
                        'recipient_builder_data' => [
                            'query'     => null,
                            'custom'    => $tags[0] != '' ? [
                                'tag'   => [
                                    'state' => 'all',
                                    'tags'  => $tags
                                ],
                            ] : null,
                            'customers' => null,
                        ]
                    ]
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
                    'title'   => __('customer mailshot'),
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
                                'name'       => 'org.models.shop.customer-mailshot.store',
                                'parameters' => [
                                    'shop' => $parent->id,
                                ]
                            ],
                            default => [
                                'name' => 'org.models.customer-mailshot.store',
                            ],
                        }
                ],

            ]
        );
    }


    public function getBreadcrumbs(array $routeParameters): array
    {
        return array_merge(
            IndexCustomerMailshots::make()->getBreadcrumbs(
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
