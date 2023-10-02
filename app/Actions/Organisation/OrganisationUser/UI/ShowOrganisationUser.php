<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\Helpers\History\ShowHistories;
use App\Actions\InertiaAction;
use App\Actions\Traits\WithElasticsearch;
use App\Actions\UI\Customer\SysAdmin\ShowSysAdminDashboard;
use App\Http\Resources\History\HistoryResource;

use App\Models\Auth\OrganisationUser;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowOrganisationUser extends InertiaAction
{
    use WithElasticsearch;

    public function asController(OrganisationUser $organisationUser, ActionRequest $request): OrganisationUser
    {
        $this->initialisation($request)->withTab(OrganisationUserTabsEnum::values());
        return $organisationUser;
    }

    public function jsonResponse(OrganisationUser $organisationUser): OrganisationUserResource
    {
        return new OrganisationUserResource($organisationUser);
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->organisationUser()->can('sysadmin.organisationUsers.edit');
        return $request->organisationUser()->can("sysadmin.view");
    }

    public function htmlResponse(OrganisationUser $organisationUser, ActionRequest $request): Response
    {
        $this->validateAttributes();

        return Inertia::render(
            'SysAdmin/OrganisationUser',
            [
                'title'       => __('organisationUser'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($organisationUser, $request),
                    'next'     => $this->getNext($organisationUser, $request),
                ],
                'pageHead' => [
                    'title'   => $organisationUser->organisationUsername,
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => OrganisationUserTabsEnum::navigation()
                ],

                OrganisationUserTabsEnum::SHOWCASE->value => $this->tab == OrganisationUserTabsEnum::SHOWCASE->value ?
                    fn () => new OrganisationUserResource($organisationUser)
                    : Inertia::lazy(fn () => new OrganisationUserResource($organisationUser)),

                OrganisationUserTabsEnum::REQUEST_LOGS->value => $this->tab == OrganisationUserTabsEnum::REQUEST_LOGS->value ?
                    fn () => OrganisationUserRequestLogsResource::collection(ShowOrganisationUserRequestLogs::run($organisationUser->organisationUsername))
                    : Inertia::lazy(fn () => OrganisationUserRequestLogsResource::collection(ShowOrganisationUserRequestLogs::run($organisationUser->organisationUsername))),

                OrganisationUserTabsEnum::HISTORY->value => $this->tab == OrganisationUserTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(ShowHistories::run($organisationUser))
                    : Inertia::lazy(fn () => HistoryResource::collection(ShowHistories::run($organisationUser)))

            ]
        )->table(ShowOrganisationUserRequestLogs::make()->tableStructure())
            ->table(IndexHistories::make()->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {

        $headCrumb = function (OrganisationUser $organisationUser, array $routeParameters, string $suffix) {
            return [
                [

                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('organisationUsers')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $organisationUser->organisationUsername,
                        ],

                    ],
                    'suffix' => $suffix

                ],
            ];
        };

        return match ($routeName) {
            'sysadmin.organisationUsers.show',
            'sysadmin.organisationUsers.edit' =>

            array_merge(
                ShowSysAdminDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    $routeParameters['organisationUser'],
                    [
                        'index' => [
                            'name'       => 'sysadmin.organisationUsers.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'sysadmin.organisationUsers.show',
                            'parameters' => [$routeParameters['organisationUser']->organisationUsername]
                        ]
                    ],
                    $suffix
                ),
            ),


            default => []
        };

    }

    public function getPrevious(OrganisationUser $organisationUser, ActionRequest $request): ?array
    {
        $previous = OrganisationUser::where('organisationUsername', '<', $organisationUser->organisationUsername)->orderBy('organisationUsername', 'desc')->first();
        return $this->getNavigation($previous, $request->route()->getName());

    }

    public function getNext(OrganisationUser $organisationUser, ActionRequest $request): ?array
    {
        $next = OrganisationUser::where('organisationUsername', '>', $organisationUser->organisationUsername)->orderBy('organisationUsername')->first();
        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?OrganisationUser $organisationUser, string $routeName): ?array
    {
        if (!$organisationUser) {
            return null;
        }
        return match ($routeName) {
            'sysadmin.organisationUsers.show' => [
                'label' => $organisationUser->organisationUsername,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'organisationUser' => $organisationUser->organisationUsername
                    ]

                ]
            ]
        };
    }
}
