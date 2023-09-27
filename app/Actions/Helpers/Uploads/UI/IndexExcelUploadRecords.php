<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Mar 2023 19:12:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\HumanResources\ShowHumanResourcesDashboard;
use App\Http\Resources\HumanResources\EmployeeInertiaResource;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\InertiaTable\InertiaTable;
use App\Models\Helpers\UploadRecord;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexExcelUploadRecords extends InertiaAction
{
    public function handle($prefix=null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('data', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder=QueryBuilder::for(UploadRecord::class);

        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('status')
            ->with('excel')
            ->allowedSorts(['status'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix=null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->column(key: 'filename', label: __('filename'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'data', label: __('data'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'status', label: __('status'), canBeHidden: false)
                ->column(key: 'comment', label: __('comment'), canBeHidden: false)
                ->defaultSort('status');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('hr.edit');

        return $request->user()->hasPermissionTo('hr.view');

    }


    public function jsonResponse(LengthAwarePaginator $employees): AnonymousResourceCollection
    {
        return EmployeeResource::collection($employees);
    }

    public function htmlResponse(LengthAwarePaginator $employees): Response
    {
        return Inertia::render(
            'HumanResources/Employees',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('employees'),
                'pageHead'    => [
                    'title'  => __('employees'),
                ],
                'data'        => EmployeeInertiaResource::collection($employees),
            ]
        )->table($this->tableStructure());
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle();
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowHumanResourcesDashboard())->getBreadcrumbs(),
            [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => [
                            'name' => 'org.hr.employees.index'
                        ],
                        'label' => __('employees'),
                        'icon'  => 'fal fa-bars',
                    ],

                ]
            ]
        );
    }
}
