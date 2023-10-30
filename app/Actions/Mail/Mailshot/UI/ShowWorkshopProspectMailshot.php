<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 13:51:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Enums\UI\Organisation\MailshotTabsEnum;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowWorkshopProspectMailshot extends InertiaAction
{
    use WithActionButtons;


    public function handle(Mailshot $mailshot): Mailshot
    {
        return $mailshot;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('crm.prospects.edit');
        $this->canDelete = $request->user()->hasPermissionTo('crm.prospects.edit');

        return
            (
                $request->user()->hasPermissionTo('crm.prospects.view')
            );
    }

    public function asController(Shop $shop, Mailshot $mailshot, ActionRequest $request): Mailshot
    {
        $this->initialisation($request)->withTab(MailshotTabsEnum::values());

        return $this->handle($mailshot);
    }


    public function htmlResponse(Mailshot $mailshot, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/Prospects/WorkshopMailshot',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($mailshot, $request),
                    'next'     => $this->getNext($mailshot, $request),
                ],
                'title'       => $mailshot->subject,
                'pageHead'    => [
                    'title'     => $mailshot->subject,
                    'icon'      => [
                        'tooltip' => __('mailshot'),
                        'icon'    => 'fal fa-mail-bulk'
                    ],
                    'iconRight' => $mailshot->state->stateIcon()[$mailshot->state->value],
                    'actions'   => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit workshop'),
                            'route' => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters()),
                            ]
                        ],
                    ]

                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => MailshotTabsEnum::navigation()
                ],


            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = null): array
    {
        $headCrumb = function (string $type, Mailshot $mailshot, array $routeParameters, string $suffix = null) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('mailshots')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $mailshot->subject,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $mailshot->subject
                    ],
                    'suffix'         => $suffix
                ],
            ];
        };


        return match ($routeName) {
            'customer.mailshots.mailshots.show',
            'customer.mailshots.mailshots.edit' =>
            array_merge(
                IndexProspects::make()->getBreadcrumbs(
                    'org.crm.shop.prospects.index',
                    $routeParameters
                ),
                $headCrumb(
                    'modelWithIndex',
                    Mailshot::firstWhere('slug', $routeParameters['mailshot']),
                    [
                        'index' => [
                            'name'       => 'customer.mailshots.mailshots.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.mailshots.mailshots.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            default => []
        };
    }

    public function getPrevious(Mailshot $mailshot, ActionRequest $request): ?array
    {
        if (class_basename($this->parent) == 'PortfolioWebsite') {
            // todo, need to use a join
            $previous = null;
        } else {
            $previous = Mailshot::where('slug', '<', $mailshot->slug)->orderBy('slug')->first();
        }

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Mailshot $mailshot, ActionRequest $request): ?array
    {
        if (class_basename($this->parent) == 'PortfolioWebsite') {
            // todo, need to use a join
            $next = null;
        } else {
            $next = Mailshot::where('slug', '>', $mailshot->slug)->orderBy('slug')->first();
        }


        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Mailshot $mailshot, string $routeName): ?array
    {
        if (!$mailshot) {
            return null;
        }


        return match ($routeName) {
            'customer.mailshots.mailshots.show',
            'customer.mailshots.mailshots.edit' => [
                'label' => $mailshot->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'mailshot' => $mailshot->slug
                    ]
                ]
            ],
        };
    }

}
