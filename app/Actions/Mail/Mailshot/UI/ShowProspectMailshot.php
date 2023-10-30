<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 12:42:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Traits\Actions\WithActionButtons;

use App\Enums\UI\Organisation\MailshotTabsEnum;
use App\Http\Resources\CRM\ProspectMailshotResource;
use App\Http\Resources\History\HistoryResource;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowProspectMailshot extends InertiaAction
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
            'CRM/Prospects/Mailshot',
            [
                'breadcrumbs'                      => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'                       => [
                    'previous' => $this->getPrevious($mailshot, $request),
                    'next'     => $this->getNext($mailshot, $request),
                ],
                'title'                            => $mailshot->subject,
                'pageHead'                         => [
                    'title'       => $mailshot->subject,
                    'icon'        => [
                        'tooltip' => __('mailshot'),
                        'icon'    => 'fal fa-mail-bulk'
                    ],
                    'iconRight'   => $mailshot->state->stateIcon()[$mailshot->state->value],
                    'iconActions' => [
                        $this->canDelete ? $this->getDeleteActionIcon($request) : null,
                        $this->canEdit ? $this->getEditActionIcon($request) : null,
                    ],
                    'actions'     => [


                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'primary',
                            'label' => __('Workshop'),
                            'icon'  => ["fal", "fa-drafting-compass"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'workshop', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                    ],
                ],
                'tabs'                             => [
                    'current'    => $this->tab,
                    'navigation' => MailshotTabsEnum::navigation()
                ],
                MailshotTabsEnum::SHOWCASE->value  => $this->tab == MailshotTabsEnum::SHOWCASE->value
                    ?
                    fn () => ProspectMailshotResource::make($mailshot)->getArray()
                    : Inertia::lazy(
                        fn () => ProspectMailshotResource::make($mailshot)->getArray()
                    ),

                /*
                MailshotTabsEnum::RECIPIENTS->value => $this->tab == MailshotTabsEnum::RECIPIENTS->value

                    ?
                    fn () => SnapshotResource::collection(
                        IndexSnapshots::run(
                            parent: $mailshot,
                            prefix: MailshotTabsEnum::RECIPIENTS->value
                        )
                    )
                    : Inertia::lazy(fn () => SnapshotResource::collection(
                        IndexSnapshots::run(
                            parent: $mailshot,
                            prefix: MailshotTabsEnum::RECIPIENTS->value
                        )
                    )),
*/
                MailshotTabsEnum::CHANGELOG->value => $this->tab == MailshotTabsEnum::CHANGELOG->value
                    ?
                    fn () => HistoryResource::collection(
                        IndexHistory::run(
                            model: $mailshot,
                            prefix: MailshotTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => HistoryResource::collection(
                        IndexHistory::run(
                            model: $mailshot,
                            prefix: MailshotTabsEnum::CHANGELOG->value
                        )
                    )),

            ]
        )->table(
            IndexHistory::make()->tableStructure(
                prefix: MailshotTabsEnum::CHANGELOG->value
            )
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
        $previous = Mailshot::where('slug', '<', $mailshot->slug)->orderBy('slug')->first();


        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Mailshot $mailshot, ActionRequest $request): ?array
    {
        $next = Mailshot::where('slug', '>', $mailshot->slug)->orderBy('slug')->first();


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
