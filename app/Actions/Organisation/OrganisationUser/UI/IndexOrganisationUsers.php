<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\SysAdmin\ShowSysAdminDashboard;
use App\Enums\UI\UsersTabsEnum;
use App\Http\Resources\Auth\UserResource;
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
                    ->orWhere('organisation_users.username', 'ILIKE', "$value%");
            });
        });


        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(OrganisationUser::class);
        foreach ($this->getElementGroups() as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        return $queryBuilder
            ->defaultSort('username')
            ->select(['username', 'email', 'contact_name', 'avatar_id'])
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
                ->withTitle(title: __('users'))
                ->withGlobalSearch()
                ->withModelOperations($modelOperations)
                ->column(key: 'avatar', label: ['fal', 'fa-user-circle'])
                ->column(key: 'username', label: __('username'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'roles', label: __('roles'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'permissions', label: __('permissions'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('username');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('sysadmin.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('sysadmin.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $organisationUsers): AnonymousResourceCollection
    {
        return UserResource::collection($organisationUsers);
    }


    public function htmlResponse(LengthAwarePaginator $organisationUsers, ActionRequest $request): Response
    {
        return Inertia::render(
            'SysAdmin/OrganisationUsers',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                ),
                'title'       => __('users'),
                'pageHead'    => [
                    'title'  => __('users'),
                    'actions'=> [
                        $this->canEdit ? [
                            'type'    => 'buttonGroup',
                            'buttons' => [
                                [
                                    'style' => 'secondary',
                                    'icon'  => ['fal', 'fa-upload'],
                                    'label' => 'upload',
                                    'route' => [
                                        'name'       => 'org.models.users.upload'
                                    ],
                                ],
                                [
                                    'type'  => 'button',
                                    'style' => 'create',
                                    'label' => __('employee'),
                                    'route' => [
                                        'name'       => 'org.sysadmin.employees.create',
                                        'parameters' => array_values($this->originalParameters)
                                    ]
                                ]
                            ]
                        ] : false
                    ]
                ],

                'labels' => [
                    'usernameNoSet' => __('username no set')
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => UsersTabsEnum::navigation(),
                ],

                UsersTabsEnum::USERS->value => $this->tab == UsersTabsEnum::USERS->value ?
                    fn () => UserResource::collection($organisationUsers)
                    : Inertia::lazy(fn () => UserResource::collection($organisationUsers)),

                /*
                UsersTabsEnum::USERS_REQUESTS->value => $this->tab == UsersTabsEnum::USERS_REQUESTS->value ?
                    fn () => UserRequestLogsResource::collection(IndexUserRequestLogs::run())
                    : Inertia::lazy(fn () => UserRequestLogsResource::collection(IndexUserRequestLogs::run()))
                */

            ]
        )->table(
            $this->tableStructure(
                prefix: 'users'
            )
        )
            //  ->table(IndexUserRequestLogs::make()->tableStructure())
        ;
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(UsersTabsEnum::values());

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