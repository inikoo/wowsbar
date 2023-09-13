<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:17:53 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website\UI;

use App\Actions\InertiaAction;

use App\Models\Organisation\Web\Website;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditWebsite extends InertiaAction
{
    public function handle(Website $website): Website
    {
        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('website.edit');
        return $request->user()->hasPermissionTo("website.edit");

    }

    public function asController(ActionRequest $request): Website
    {
        $website=organisation()->website;
        $this->initialisation($request);
        return $this->handle($website);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(Website $website, ActionRequest $request): Response
    {

        return Inertia::render(
            'EditModel',
            [
                    'title'       => __("Website's settings"),
                    'breadcrumbs' => $this->getBreadcrumbs(

                    ),

                    'pageHead'    => [
                        'title'     => __('website settings'),


                        'iconRight'    =>
                            [
                                'icon'  => ['fal', 'sliders-h'],
                                'title' => __("Website settings")
                            ],

                        'actions'   => [
                            [
                                'type'  => 'button',
                                'style' => 'exit',
                                'label' => __('Exit settings'),
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
                            'title'  => __('Registrations'),
                            'icon'   => 'fal fa-user-plus',
                            'fields' => [
                                'approval' => [
                                    'type'     => 'toggle',
                                    'label'    => __('Registrations Approval'),
                                    'value'    => false,
                                    'required' => true,
                                ],
                                'registrations_type' => [
                                    'type'     => 'radio',
                                    'mode'     => 'card',
                                    'label'    => __('Registration Type'),
                                    'value'    => [
                                            'title'        => "type B",
                                            'description'  => 'This user able to create and delete',
                                            'label'        => '17 users left',
                                            'value'        => "typeB",
                                        ],
                                    'required' => true,
                                    'options'  => [
                                        [
                                            'title'        => "type A",
                                            'description'  => 'This user able to edit',
                                            'label'        => '425 users left',
                                            'value'        => "typeA",
                                        ],
                                        [
                                            'title'        => "type B",
                                            'description'  => 'This user able to create and delete',
                                            'label'        => '17 users left',
                                            'value'        => "typeB",
                                        ],
                                    ]
                                ],
                                'web_registrations' => [
                                    'type'     => 'webRegistrations',
                                    'label'    => __('Web Registration'),
                                    'value'    => [
                                        [
                                            'key'       => 'telephone',
                                            'name'      => __('telephone'),
                                            'show'      => true,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'address',
                                            'name'      => __('address'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'company',
                                            'name'      => __('company'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'contact_name',
                                            'name'      => __('contact_name'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'registration_number',
                                            'name'      => __('registration number'),
                                            'show'      => true,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'tax_number',
                                            'name'      => __('tax number'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'terms_and_conditions',
                                            'name'      => __('terms and conditions'),
                                            'show'      => true,
                                            'required'  => true,
                                        ],
                                        [
                                            'key'       => 'marketing',
                                            'name'      => __('marketing'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                ],
                                    'required' => true,
                                    'options'  => [
                                        [
                                            'key'       => 'telephone',
                                            'name'      => __('telephone'),
                                            'show'      => true,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'address',
                                            'name'      => __('address'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'company',
                                            'name'      => __('company'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'contact_name',
                                            'name'      => __('contact name'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'registration_number',
                                            'name'      => __('registration number'),
                                            'show'      => true,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'tax_number',
                                            'name'      => __('tax number'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'terms_and_conditions',
                                            'name'      => __('terms and conditions'),
                                            'show'      => true,
                                            'required'  => false,
                                        ],
                                        [
                                            'key'       => 'marketing',
                                            'name'      => __('marketing'),
                                            'show'      => false,
                                            'required'  => false,
                                        ],
                                ]
                                ]
                            ]
                        ],
                            [
                                'title' => 'state',
                                'icon'  => 'fal signal-stream',
                                'fields'=> [
                                    'state' => [
                                        'type'     => 'action',
                                        'label'    => __('Launch'),
                                        'icon'     => 'fal fa-rocket-launch',
                                        'value'    => false,
                                        'required' => true,
                                        'button'   => [
                                            'method'    => 'patch',
                                            'data'      => [
                                                'state' => 'live'
                                            ],
                                            'route'     => [
                                                'name'  => 'org.models.website.state.update'
                                            ]
                                        ]
                                    ],
                                ]
                            ]
                    ],
                        'args'      => [
                            'updateRoute' => [
                                'name'       => 'org.models.website.update',
                                'parameters' => $website->slug
                            ],
                        ]
                    ],

                ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return ShowWebsite::make()->getBreadcrumbs(
            suffix: '('.__('settings').')'
        );
    }


}
