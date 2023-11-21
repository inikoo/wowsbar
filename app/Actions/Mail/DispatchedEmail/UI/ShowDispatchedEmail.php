<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 12:42:51 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\DispatchedEmail\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Enums\UI\Organisation\DispatchedEmailTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Mail\DispatchedEmailResource;
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Mailshot;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowDispatchedEmail extends InertiaAction
{
    use WithActionButtons;

    public function handle(DispatchedEmail $dispatchedEmail): DispatchedEmail
    {
        return $dispatchedEmail;
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

    public function asController(Shop $shop, Mailshot $mailshot, DispatchedEmail $dispatchedEmail, ActionRequest $request): DispatchedEmail
    {
        $this->initialisation($request)->withTab(DispatchedEmailTabsEnum::values());

        return $this->handle($dispatchedEmail);
    }

    public function htmlResponse(DispatchedEmail $dispatchedEmail, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/Prospects/DispatchedEmail',
            [
                'breadcrumbs'                     => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'                      => [
                    'previous' => [],
                    'next'     => [],
                ],
                'title'                           => $dispatchedEmail->mailshotRecipient->recipient['name'],
                'pageHead'                        => [
                    'title'     => $dispatchedEmail->mailshotRecipient->recipient['name'],
                    'icon'      => [
                        'tooltip' => __('dispatched email'),
                        'icon'    => 'fal fa-mail-bulk'
                    ],
                    'iconRight' => $dispatchedEmail->state->stateIcon()[$dispatchedEmail->state->value],
                ],
                'tabs'                            => [
                    'current'    => $this->tab,
                    'navigation' => DispatchedEmailTabsEnum::navigation()
                ],
                DispatchedEmailTabsEnum::SHOWCASE->value => $this->tab == DispatchedEmailTabsEnum::SHOWCASE->value
                    ?
                    fn () => DispatchedEmailResource::make($dispatchedEmail)->getArray()
                    : Inertia::lazy(
                        fn () => DispatchedEmailResource::make($dispatchedEmail)->getArray()
                    ),

                DispatchedEmailTabsEnum::CHANGELOG->value => $this->tab == DispatchedEmailTabsEnum::CHANGELOG->value
                    ?
                    fn () => HistoryResource::collection(
                        IndexHistory::run(
                            model: $dispatchedEmail,
                            prefix: DispatchedEmailTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => HistoryResource::collection(
                        IndexHistory::run(
                            model: $dispatchedEmail,
                            prefix: DispatchedEmailTabsEnum::CHANGELOG->value
                        )
                    )),

            ]
        )->table(
            IndexHistory::make()->tableStructure(
                prefix: DispatchedEmailTabsEnum::CHANGELOG->value
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
            'org.crm.shop.prospects.mailshots.show',
            'org.crm.shop.prospects.mailshots.edit' =>
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
                            'name'       => 'org.crm.shop.prospects.mailshots.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.shop.prospects.mailshots.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            default => []
        };
    }


}
