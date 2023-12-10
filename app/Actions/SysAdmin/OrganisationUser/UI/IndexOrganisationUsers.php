<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\SysAdmin\OrganisationUser\IndexOrganisationUserRequestLogs;
use App\Actions\UI\Organisation\SysAdmin\ShowSysAdminDashboard;
use App\Enums\UI\Organisation\OrganisationUsersTabsEnum;
use App\Http\Resources\Auth\OrganisationUserRequestLogsResource;
use App\Http\Resources\Auth\OrganisationUserResource;
use App\Http\Resources\History\HistoryResource;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\OrganisationUser;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexOrganisationUsers extends InertiaAction
{
    protected function getElementGroups(): array
    {
        return
            [
                'status' => [
                    'label'    => __('Status'),
                    'elements' => [
                        'active'    =>
                            [
                                __('Active'),
                                organisation()->stats->number_organisation_users_status_active
                            ],
                        'suspended' => [
                            __('Suspended'),
                            organisation()->stats->number_organisation_users_status_inactive
                        ]
                    ],
                    'engine'   => function ($query, $elements) {
                        $query->where('status', array_pop($elements) === 'active');
                    }

                ]
            ];
    }


    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('contact_name', $value)
                    ->orWhereStartWith('organisation_users.username', $value);
            });
        });


        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(OrganisationUser::class);
        foreach ($this->getElementGroups() as $key => $elementGroup) {
            /** @noinspection PhpUndefinedMethodInspection */
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('username')
            ->allowedSorts(['username', 'email', 'contact_name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            foreach ($this->getElementGroups() as $key => $elementGroup) {
                $table->elementGroup(
                    key: $key,
                    label: $elementGroup['label'],
                    elements: $elementGroup['elements']
                );
            }


            $table
                ->withGlobalSearch()
                ->withModelOperations($modelOperations)
                ->column(key: 'avatar', label: ['fal', 'fa-user-circle'])
                ->column(key: 'username', label: __('username'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'roles', label: __('roles'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('username');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('sysadmin.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('sysadmin.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $organisationUsers): AnonymousResourceCollection
    {
        return OrganisationUserResource::collection($organisationUsers);
    }


    public function htmlResponse(LengthAwarePaginator $organisationUsers, ActionRequest $request): Response
    {
        $organisation = organisation();

        return Inertia::render(
            'SysAdmin/OrganisationUsers',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                ),
                'title'       => __('users'),
                'pageHead'    => [
                    'title'   => __('users'),
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('create user (guest)'),
                            'route' => [
                                'name'       => 'org.sysadmin.guests.create',
                                'parameters' => []
                            ]
                        ] : null
                    ]
                ],

                'labels' => [
                    'usernameNoSet' => __('username no set')
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => OrganisationUsersTabsEnum::navigation(),
                ],

                OrganisationUsersTabsEnum::USERS->value => $this->tab == OrganisationUsersTabsEnum::USERS->value ?
                    fn () => OrganisationUserResource::collection($organisationUsers)
                    : Inertia::lazy(fn () => OrganisationUserResource::collection($organisationUsers)),


                OrganisationUsersTabsEnum::USERS_REQUESTS->value => $this->tab == OrganisationUsersTabsEnum::USERS_REQUESTS->value ?
                    fn () => OrganisationUserRequestLogsResource::collection(IndexOrganisationUserRequestLogs::run($organisation))
                    : Inertia::lazy(fn () => OrganisationUserRequestLogsResource::collection(IndexOrganisationUserRequestLogs::run($organisation))),

                OrganisationUsersTabsEnum::SYSADMIN_HISTORY->value => $this->tab == OrganisationUsersTabsEnum::SYSADMIN_HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(model: OrganisationUser::class, prefix: OrganisationUsersTabsEnum::SYSADMIN_HISTORY->value))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(model: OrganisationUser::class, prefix: OrganisationUsersTabsEnum::SYSADMIN_HISTORY->value)))


            ]
        )->table($this->tableStructure(prefix: OrganisationUsersTabsEnum::USERS->value))
            ->table(IndexHistory::make()->tableStructure(prefix: OrganisationUsersTabsEnum::SYSADMIN_HISTORY->value))
            ->table(IndexOrganisationUserRequestLogs::make()->tableStructure(parent: $organisation, prefix: OrganisationUsersTabsEnum::USERS_REQUESTS->value));
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(OrganisationUsersTabsEnum::values());

        return $this->handle(prefix: 'users');
    }

    public function getBreadcrumbs(string $routeName): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('users'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.sysadmin.users.index' =>
            array_merge(
                ShowSysAdminDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'org.sysadmin.users.index',
                        null
                    ]
                ),
            ),


            default => []
        };
    }

}
