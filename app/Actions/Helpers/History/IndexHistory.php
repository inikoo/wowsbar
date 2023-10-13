<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 09:38:55 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\History;

use App\Actions\Auth\User\Traits\WithFormattedUserHistories;
use App\InertiaTable\InertiaTable;
use Closure;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use OwenIt\Auditing\Models\Audit;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexHistory
{
    use AsAction;
    use WithAttributes;
    use WithFormattedUserHistories;

    public string $model;

    public function handle($model, $prefix = null): LengthAwarePaginator|array|bool
    {
        $this->model = class_basename($model);

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('user_type', $value)
                    ->orWhere('user_type', 'ILIKE', "$value%")
                    ->orWhere('url', 'ILIKE', "$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Audit::class);

        $queryBuilder->orderBy('id', 'DESC');
        $queryBuilder->where('auditable_type', $this->model);
        if (isset($model->id)) {
            $queryBuilder->where('auditable_id', $model->id);
        }

        return $queryBuilder
            ->defaultSort('audits.created_at')
            ->allowedSorts(['auditable_id', 'auditable_type', 'user_type', 'url','created_at'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure($prefix=null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($exportLinks, $prefix) {

            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            $table
                ->withGlobalSearch()
                ->withExportLinks($exportLinks)
                ->column(key: 'created_at', label: __('Date'), canBeHidden: false, sortable: true)

                ->column(key: 'user_id', label: __('User'), canBeHidden: false, sortable: true)

                //->column(key: 'ip_address', label: __('IP Address'), canBeHidden: false, sortable: true, searchable: true)
                //->column(key: 'url', label: __('URL'), canBeHidden: false, sortable: true, searchable: true)
                //->column(key: 'old_values', label: __('Old Values'), canBeHidden: false, sortable: true)
                //->column(key: 'new_values', label: __('New Values'), canBeHidden: false, sortable: true)
                ->column(key: 'action', label: __('Action'), canBeHidden: false, sortable: true)
        //        ->column(key: 'auditable_type', label: __('Module'), canBeHidden: false)
                ->defaultSort('ip_address');
        };
    }
}
