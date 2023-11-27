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

class EditMailshotSettings extends InertiaAction
{
    use WithProspectMailshotNavigation;

    public function handle(Shop $shop): Shop
    {
        return $shop;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.prospects.edit');

        return $request->user()->hasPermissionTo("crm.prospects.edit");
    }

    public function asController(Shop $shop, ActionRequest $request): Shop
    {
        $this->initialisation($request);

        return $this->handle($shop);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(Shop $shop, ActionRequest $request): Response
    {
        $sections['unsubscribe'] = [
            'label'  => __('Mailshot unsubscribe'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                'title' => [
                    'type'     => 'input',
                    'label'    => __('title'),
                    'value'    => Arr::get($shop->settings, 'mailshot.unsubscribe.title'),
                    'required' => true,
                ],
                'description' => [
                    'type'     => 'input',
                    'label'    => __('description'),
                    'value'    => Arr::get($shop->settings, 'mailshot.unsubscribe.description'),
                    'required' => true,
                ],
            ]
        ];

        $sections['sender_email'] = [
            'label'  => __('Delete'),
            'icon'   => 'fal fa-trash-alt',
            'fields' => [
                'sender_email_address' => [
                    'type'     => 'input',
                    'label'    => __('sender email address'),
                    'value'    => $shop->sender_email_address,
                    'required' => true,
                ],
            ]
        ];

        $currentSection = 'unsubscribe';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'title'       => __("Edit mailshot"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($shop, $request),
                    'next'     => $this->getNext($shop, $request),
                ],
                'pageHead' => [
                    'title' => $shop->subject,
                    'icon'  => [
                        'tooltip' => __('mailshot'),
                        'icon'    => 'fal fa-sign'
                    ],
                    'iconRight' => $shop->state->stateIcon()[$shop->state->value],
                    'actions'   => [
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
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.mailshot.update',
                            'parameters' => $shop->id
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
