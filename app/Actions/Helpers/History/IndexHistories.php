<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Mon, 12 Jun 2023 16:00:25 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\History;

use App\Actions\Tenant\Auth\User\Traits\WithFormattedUserHistories;
use App\InertiaTable\InertiaTable;
use Closure;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use OwenIt\Auditing\Models\Audit;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexHistories
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

        $queryBuilder = QueryBuilder::for(Audit::class);

        $queryBuilder->where('auditable_type', $this->model);

        return $queryBuilder
            ->defaultSort('user_type')
            ->allowedSorts(['auditable_id', 'auditable_type', 'user_type', 'url'])
            ->allowedFilters([$globalSearch,'auditable_id', 'auditable_type'])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($exportLinks) {
            $table
                ->name('hst')
                ->pageName('historyPage')
                ->withExportLinks($exportLinks)
                ->column(key: 'ip_address', label: __('IP Address'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'user_id', label: __('User ID'), canBeHidden: false, sortable: true)
                ->column(key: 'url', label: __('URL'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'old_values', label: __('Old Values'), canBeHidden: false, sortable: true)
                ->column(key: 'new_values', label: __('New Values'), canBeHidden: false, sortable: true)
                ->column(key: 'event', label: __('Action'), canBeHidden: false, sortable: true)
                ->column(key: 'auditable_type', label: __('Module'), canBeHidden: false)
                ->column(key: 'datetime', label: __('Date & Time'), canBeHidden: false, sortable: true)
                ->defaultSort('ip_address');
        };
    }
}
