<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\Queries\UI\IndexProspectQueries;
use App\Models\Helpers\Query;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Exception;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditProspectMailshot extends InertiaAction
{
    use WithProspectMailshotNavigation;

    public function handle(Mailshot $mailshot): Mailshot
    {
        return $mailshot;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.prospects.edit');

        return $request->user()->hasPermissionTo("crm.prospects.edit");
    }

    public function asController(Shop $shop, Mailshot $mailshot, ActionRequest $request): Mailshot
    {
        $this->initialisation($request);

        return $this->handle($mailshot);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(Mailshot $mailshot, ActionRequest $request): Response
    {
        $query = Query::findOrFail(Arr::get($mailshot->recipients_recipe, 'query_id'));

        $filter = Arr::get($query->constrains, 'filter', []);
        $tags = Arr::get($filter, array_key_first($filter), []);
        $lastContact = Arr::get($query->arguments, '__date__');
        $lastContactValue = Arr::get($lastContact, 'value');

        $sections['properties'] = [
            'label' => __('Mailshot properties'),
            'icon' => 'fal fa-sliders-h',
            'fields' => [
                'name' => [
                    'type' => 'input',
                    'label' => __('subject'),
                    'value' => $mailshot->subject,
                    'required' => true,
                ],
                'query' => [
                    'type' => 'prospectQueryChooser',
                    'label' => __('prospects query'),
                    'required' => true,
                    'full'     => true,
                    'options' => [
                        'query' => IndexProspectQueries::run(),
                        'custom' => '',
                    ],
                    'value' => [
                        'recipient_builder_type' => 'query',
                        'recipient_builder_data' => [
                            'query' => $query->id,
                            'custom' => [
                                'query' => (array)Arr::get($query->constrains, 'with', []),
                                'tag' => [
                                    'state' => array_key_first($filter),
                                    'tags' => $tags
                                ],
                                'last_contact' => [
                                    'state' => $lastContact != null,
                                    'data' => [
                                        'unit' => Arr::get($lastContactValue, 'unit'),
                                        'quantity' => Arr::get($lastContactValue, 'quantity')
                                    ]
                                ],
                            ],
                            'prospects' => null,
                        ]
                    ]
                ],


            ]
        ];


        $sections['delete'] = [
            'label' => __('Delete'),
            'icon' => 'fal fa-trash-alt',
            'fields' => [
                'name' => [
                    'type' => 'action',
                    'action' => [
                        'type' => 'button',
                        'style' => 'delete',
                        'label' => __('delete mailshot'),
                        'method' => 'delete',
                        'route' => [
                            'name' => 'org.models.mailshot.delete',
                            'parameters' => [
                                'mailshot' => $mailshot->id
                            ],
                            'method' => 'delete'
                        ]
                    ],
                ]
            ]
        ];

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'title' => __("Edit mailshot"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($mailshot, $request),
                    'next' => $this->getNext($mailshot, $request),
                ],
                'pageHead' => [
                    'title' => $mailshot->subject,
                    'icon' => [
                        'tooltip' => __('mailshot'),
                        'icon' => 'fal fa-sign'
                    ],
                    'iconRight' => $mailshot->state->stateIcon()[$mailshot->state->value],
                    'actions' => [
                        [
                            'type' => 'button',
                            'style' => 'exit',
                            'label' => __('Exit edit'),
                            'route' => [
                                'name' => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ],
                ],
                'formData' => [
                    'current' => $currentSection,
                    'blueprint' => $sections,
                    'args' => [
                        'updateRoute' => [
                            'name' => 'org.models.mailshot.update',
                            'parameters' => $mailshot->id
                        ],
                    ]
                ],

            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowProspectMailshot::make()->getBreadcrumbs(
            $routeName,
            $routeParameters,
            suffix: '(' . __('editing') . ')'
        );
    }


}
