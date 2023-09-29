<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 15:35:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\UI\CRM\Mailshot;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\SysAdmin\ShowSysAdminDashboard;
use App\Http\Resources\SysAdmin\GuestResource;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\Guest;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexMailshots extends InertiaAction
{
    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('contact_name', $value)
                    ->orWhere('guests.slug', 'ILIKE', "$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Guest::class)
            ->leftJoin(
                'users',
                function ($leftJoin) {
                    $leftJoin
                        ->on('users.parent_id', '=', 'guests.id')
                        ->where('users.parent_type', '=', 'Guest');
                }
            )->leftJoin('user_stats', 'user_stats.user_id', 'users.id');
        foreach ($this->getElementGroups() as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        return $queryBuilder
            ->defaultSort('guests.slug')
            ->select(['guests.id', 'slug', 'guests.contact_name','guests.email','number_logins','last_login_at','number_failed_logins','last_failed_login_at'])
            ->allowedSorts(['slug', 'contact_name','email','number_logins','last_login_at','number_failed_logins','last_failed_login_at'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure($prefix = null): Closure
    {
        return function (InertiaTable $table) use ($prefix) {
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
                ->withEmptyState(
                    [
                        'title'       => __('no guest'),
                        'description' => $this->canEdit ? __('Get started by creating a new guest.') : null,
                        'count'       => customer()->stats->number_guests_status_active,
                        'action'      => $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new guest'),
                            'label'   => __('guest'),
                            'route'   => [
                                'name'       => 'sysadmin.guests.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : null
                    ]
                )
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'email', label: __('email'), canBeHidden: false, sortable: true, searchable: true)

                ->defaultSort('slug');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('sysadmin.guests.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('sysadmin.view')
            );
    }


    public function jsonResponse(LengthAwarePaginator $guests): AnonymousResourceCollection
    {
        return GuestResource::collection($guests);
    }


    public function htmlResponse(LengthAwarePaginator $guests, ActionRequest $request): Response
    {
        return Inertia::render(
            'SysAdmin/Guests',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('guests'),
                'pageHead'    => [
                    'title'  => __('guests'),
                    'actions'=> [
                        $this->canEdit ? [
                            'type'    => 'buttonGroup',
                            'buttons' => [
                                [
                                    'style' => 'secondary',
                                    'icon'  => ['fal', 'fa-upload'],
                                    'label' => 'upload',
                                    'route' => [
                                        'name'       => 'org.models.guests.upload'
                                    ],
                                ],
                                [
                                    'type'  => 'button',
                                    'style' => 'create',
                                    'label' => __('guest'),
                                    'route' => [
                                        'name'       => 'sysadmin.guests.create',
                                        'parameters' => array_values($request->route()->originalParameters())
                                    ]
                                ]
                            ]
                        ] : false
                    ]
                ],
                'data'        => GuestResource::collection($guests),
            ]
        )->table($this->tableStructure());
    }


    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle();
    }

    public function getBreadcrumbs($suffix = null): array
    {
        return array_merge(
            (new ShowSysAdminDashboard())->getBreadcrumbs(),
            [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => [
                            'name' => 'sysadmin.guests.index',
                        ],
                        'label' => __('guests'),
                        'icon'  => 'fal fa-bars',
                    ],
                    'suffix' => $suffix

                ]
            ]
        );
    }


}
