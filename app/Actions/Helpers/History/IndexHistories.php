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

class IndexHistories
{
    use AsAction;
    use WithAttributes;
    use WithFormattedUserHistories;

    public function handle($model): LengthAwarePaginator|array|bool
    {
        return $model->audits()->with('user')->paginate();
    }

    public function tableStructure(?array $modelOperations = null): Closure
    {
        return function (InertiaTable $table) {
            $table
                ->name('hst')
                ->pageName('historyPage')
                ->column(key: 'ip_address', label: __('IP Address'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'user_id', label: __('User ID'), canBeHidden: false, sortable: true)
//                ->column(key: 'slug', label: __('Slug'), canBeHidden: false, sortable: true)
//                ->column(key: 'user_name', label: __('User Name'), canBeHidden: false, sortable: true)
                ->column(key: 'url', label: __('URL'), canBeHidden: false, sortable: true)
                ->column(key: 'old_values', label: __('Old Values'), canBeHidden: false, sortable: true)
                ->column(key: 'new_values', label: __('New Values'), canBeHidden: false, sortable: true)
                ->column(key: 'event', label: __('Event'), canBeHidden: false, sortable: true)
                ->column(key: 'auditable_type', label: __('Auditable Type'), canBeHidden: false)
                ->column(key: 'auditable_id', label: __('Auditable ID'), canBeHidden: false)
                ->column(key: 'datetime', label: __('Date & Time'), canBeHidden: false, sortable: true)
                ->defaultSort('ip_address');
        };
    }
}
