<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:55:05 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Appointment\UI;

use App\Actions\InertiaAction;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Appointment;
use App\Models\Helpers\Snapshot;
use App\Models\Portfolio\Banner;
use App\Models\Web\Webpage;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\QueryBuilder;

class IndexAppointment extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->get('customerUser')->hasPermissionTo('portfolio.banners.view')
            );
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Banner|Webpage $parent, $prefix = null): LengthAwarePaginator
    {
        $queryBuilder = QueryBuilder::for(Appointment::class);

        return $queryBuilder
            ->defaultSort('-schedule_at')
            ->allowedSorts(['schedule_at'])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(Appointment $appointment, ?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix . 'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('No appointment'),
                        'count' => 0
                    ]
                );
            if ($exportLinks) {
                $table->withExportLinks($exportLinks);
            }


            $table->column(key: 'customer_name', label: 'customer name')
                ->column(key: 'schedule_at', label: __('schedule at'), sortable: true)
                ->column(key: 'state', label: __('state'))
                ->column(key: 'type', label: __('type'))
                ->column(key: 'event', label: __('event'))
                ->column(key: 'event_address', label: __('event address'))
                ->defaultSort('published_at');
        };
    }


}
