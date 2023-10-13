<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\Organisation\OrganisationUser\IndexOrganisationUserRequestLogs;
use App\Actions\Traits\WithElasticsearch;
use App\Actions\UI\Customer\SysAdmin\ShowSysAdminDashboard;
use App\Enums\UI\Organisation\OrganisationUserTabsEnum;
use App\Http\Resources\Auth\OrganisationUserRequestLogsResource;
use App\Http\Resources\Auth\OrganisationUserResource;
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
        $this->canEdit = $request->user()->can('sysadmin.organisationUsers.edit');

        return $request->user()->can("sysadmin.view");
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
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($organisationUser, $request),
                    'next'     => $this->getNext($organisationUser, $request),
                ],
                'pageHead'    => [
                    'title'   => $organisationUser->username,
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : [],
                    ]
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => OrganisationUserTabsEnum::navigation()
                ],

                OrganisationUserTabsEnum::SHOWCASE->value => $this->tab == OrganisationUserTabsEnum::SHOWCASE->value ?
                    fn () => new OrganisationUserResource($organisationUser)
                    : Inertia::lazy(fn () => new OrganisationUserResource($organisationUser)),


                OrganisationUserTabsEnum::REQUEST_LOGS->value => $this->tab == OrganisationUserTabsEnum::REQUEST_LOGS->value ?
                    fn () => OrganisationUserRequestLogsResource::collection(IndexOrganisationUserRequestLogs::run($organisationUser))
                    : Inertia::lazy(fn () => OrganisationUserRequestLogsResource::collection(IndexOrganisationUserRequestLogs::run($organisationUser))),

                OrganisationUserTabsEnum::HISTORY->value => $this->tab == OrganisationUserTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run($organisationUser, OrganisationUserTabsEnum::HISTORY->value))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run($organisationUser, OrganisationUserTabsEnum::HISTORY->value)))

            ]
        )
            ->table(IndexOrganisationUserRequestLogs::make()->tableStructure($organisationUser))
            ->table(IndexHistory::make()->tableStructure(prefix:OrganisationUserTabsEnum::HISTORY->value));
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
                            'label' => __('users')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $organisationUser->slug,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };

        return match ($routeName) {
            'org.sysadmin.users.show',
            'org.sysadmin.users.edit' =>

            array_merge(
                ShowSysAdminDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    OrganisationUser::firstWhere('slug', $routeParameters['organisationUser']),
                    [
                        'index' => [
                            'name'       => 'org.sysadmin.users.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'org.sysadmin.users.show',
                            'parameters' => $routeParameters
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
        $previous = OrganisationUser::where('username', '<', $organisationUser->username)->orderBy('username', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(OrganisationUser $organisationUser, ActionRequest $request): ?array
    {
        $next = OrganisationUser::where('username', '>', $organisationUser->username)->orderBy('username')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?OrganisationUser $organisationUser, string $routeName): ?array
    {
        if (!$organisationUser) {
            return null;
        }

        return match ($routeName) {
            'org.sysadmin.users.show' => [
                'label' => $organisationUser->username,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'organisationUser' => $organisationUser->slug
                    ]

                ]
            ]
        };
    }
}
