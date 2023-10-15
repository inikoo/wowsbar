<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\UI;

use App\Actions\InertiaAction;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Models\Web\Website;
use Exception;
use Illuminate\Support\Arr;
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
        $this->canEdit = $request->user()->hasPermissionTo('websites.edit');

        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $this->initialisation($request);

        return $this->handle($website);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(Website $website, ActionRequest $request): Response
    {
        $sections['properties'] = [
            'label'  => __('Website properties'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                'name'   => [
                    'type'     => 'input',
                    'label'    => __('name'),
                    'value'    => $website->name,
                    'required' => true,
                ],
                'domain' => [
                    'type'      => 'inputWithAddOn',
                    'label'     => __('domain'),
                    'leftAddOn' => [
                        'label' => 'http://www.'
                    ],
                    'value'     => $website->domain,
                    'required'  => true,
                ],
            ]
        ];

        switch ($website->state) {
            case WebsiteStateEnum::IN_PROCESS:
                $sections['status'] = [
                    'label'  => __('Status'),
                    'icon'   => 'fal fa-yin-yang',
                    'fields' => [
                        'launch' => [
                            'type'   => 'action',
                            'action' => [
                                'type'   => 'button',
                                'style'  => 'positive',
                                'label'  => __('Launch'),
                                'icon'   => 'fal fa-rocket-launch',
                                'method' => 'patch',
                                'data'   => [
                                    'state' => 'live'
                                ],
                                'route'  => [
                                    'name'       => 'org.models.website.state.update',
                                    'parameters' => $website->id
                                ]

                            ]
                        ],
                    ]
                ];
                break;
            case WebsiteStateEnum::LIVE:

                if ($website->status) {
                    $sections['status'] = [
                        'label'  => __('Maintenance mode'),
                        'icon'   => 'fal fa-tools',
                        'fields' => [
                            'maintenance' => [
                                'type'   => 'action',
                                'action' => [
                                    'type'   => 'button',
                                    'style'  => 'delete',
                                    'icon'   => 'fal fa-tools',
                                    'label'  => __('Start Maintenance mode'),
                                    'method' => 'patch',
                                    'data'   => [
                                        'state'  => 'live',
                                        'status' => false
                                    ],
                                    'route'  => [
                                        'name'       => 'org.models.website.state.update',
                                        'parameters' => $website->id
                                    ]
                                ],


                            ],
                        ]
                    ];

                    $sections['close'] = [
                        'label'  => __('Close down'),
                        'icon'   => 'fal fa-do-not-enter',
                        'fields' => [
                            'maintenance' => [
                                'type'   => 'action',
                                'action' => [
                                    'type'   => 'button',
                                    'style'  => 'delete',
                                    'icon'   => 'fal fa-do-not-enter',
                                    'label'  => __('Close down website'),
                                    'method' => 'patch',
                                    'data'   => [
                                        'state' => 'closed',
                                    ],
                                    'route'  => [
                                        'name'       => 'org.models.website.state.update',
                                        'parameters' => $website->id
                                    ]
                                ],


                            ],
                        ]
                    ];
                } else {
                    $sections['status'] = [
                        'label'  => __('Stop maintenance'),
                        'icon'   => 'fal fa-tools',
                        'fields' => [
                            'maintenance' => [
                                'type'   => 'action',
                                'action' => [
                                    'type'   => 'button',
                                    'style'  => 'positive',
                                    'icon'   => 'fal fa-tools',
                                    'label'  => __('Stop Maintenance mode'),
                                    'method' => 'patch',
                                    'data'   => [
                                        'state'  => 'live',
                                        'status' => true
                                    ],
                                    'route'  => [
                                        'name'       => 'org.models.website.state.update',
                                        'parameters' => $website->id
                                    ]
                                ],


                            ],
                        ]
                    ];
                }


                break;
            case WebsiteStateEnum::CLOSED:
                $sections['reopen'] = [
                    'label'  => __('Reopen'),
                    'icon'   => 'fal fa-door-open',
                    'fields' => [
                        'launch' => [
                            'type'     => 'action',
                            'label'    => __('Reopen'),
                            'icon'     => 'fal fa-door-open',
                            'value'    => false,
                            'required' => true,
                            'button'   => [
                                'method' => 'patch',
                                'data'   => [
                                    'state' => 'live'
                                ],
                                'route'  => [
                                    'name'       => 'org.models.website.state.update',
                                    'parameters' => $website->id
                                ]
                            ]
                        ],
                    ]
                ];
                break;
        }

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'title'       => __("Website's settings"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->originalParameters()
                ),

                'pageHead' => [
                    'title' => __('website settings'),


                    'iconRight' =>
                        [
                            'icon'  => ['fal', 'sliders-h'],
                            'title' => __("Website settings")
                        ],

                    'actions' => [
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
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.website.update',
                            'parameters' => $website->id
                        ],
                    ]
                ],

            ]
        );
    }


    public function getBreadcrumbs(array $routeParameters): array
    {
        return ShowWebsite::make()->getBreadcrumbs(
            $routeParameters,
            suffix: '('.__('settings').')'
        );
    }


}
