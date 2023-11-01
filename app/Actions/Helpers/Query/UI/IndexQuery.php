<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:55:05 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query\UI;

use App\Actions\InertiaAction;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\InertiaTable\InertiaTable;
use App\Models\Helpers\Query;
use App\Models\Helpers\Snapshot;
use App\Models\Portfolio\Banner;
use App\Models\Web\Webpage;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\QueryBuilder;

class IndexQuery extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('portfolio.banners.view');
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $queryBuilder = QueryBuilder::for(Query::class);

        return $queryBuilder
            ->defaultSort('-published_at')
            ->allowedSorts(['published_at', 'published_until'])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function asController(): LengthAwarePaginator
    {
        return $this->handle();
    }

    public function tableStructure(Query $query, ?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('Query has not been published yet'),
                        'count' => 0
                    ]
                );
            if ($exportLinks) {
                $table->withExportLinks($exportLinks);
            }

            $table->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'slug', label: __('slug'), sortable: true)
                ->column(key: 'model_type', label: __('model type'), sortable: true)
                ->column(key: 'base', label: __('base'))
                ->column(key: 'filters', label: __('filters'))
                ->defaultSort('slug');
        };
    }
}
