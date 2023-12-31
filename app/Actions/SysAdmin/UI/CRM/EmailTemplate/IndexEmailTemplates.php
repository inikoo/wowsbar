<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 15:35:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\UI\CRM\EmailTemplate;

use App\Actions\InertiaAction;
use App\Actions\SysAdmin\UI\CRM\ShowCRMDashboard;
use App\Http\Resources\Mail\EmailTemplateResource;
use App\InertiaTable\InertiaTable;
use App\Models\Mail\EmailTemplate;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexEmailTemplates extends InertiaAction
{
    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereWith('name', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(EmailTemplate::class);

        return $queryBuilder
            ->defaultSort('name')
            ->allowedSorts(['name', 'id'])
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

            $table
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title'       => __('no email template'),
                        'description' => $this->canEdit ? __('Get started by creating a template.') : null,
                        'count'       => 0,
                        'action'      => $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new template'),
                            'label'   => __('email template'),
                            'route'   => [
                                'name'       => 'sysadmin.guests.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : null
                    ]
                )
                ->column(key: 'name', label: __('Name'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('name');
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


    public function jsonResponse(LengthAwarePaginator $emailTemplates): AnonymousResourceCollection
    {
        return EmailTemplateResource::collection($emailTemplates);
    }


    public function htmlResponse(LengthAwarePaginator $emailTemplates, ActionRequest $request): Response
    {
        return Inertia::render(
            'SysAdmin/EmailTemplates',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('email templates'),
                'pageHead'    => [
                    'title'   => __('email templates'),
                    'actions' => [
                        $this->canEdit ? [
                            'type'    => 'buttonGroup',
                            'buttons' => [
                                [
                                    'style' => 'secondary',
                                    'icon'  => ['fal', 'fa-upload'],
                                    'label' => 'upload',
                                    'route' => [
                                        'name' => 'org.models.guests.upload'
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
                'data'        => EmailTemplateResource::collection($emailTemplates),
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
            (new ShowCRMDashboard())->getBreadcrumbs('org.crm.shop.mailshot.email-template.index', $this->originalParameters),
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
