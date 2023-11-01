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
        return
            (
                $request->user()->tokenCan('root') or
                $request->get('customerUser')->hasPermissionTo('portfolio.banners.view')
            );
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Banner|Webpage $parent, $prefix = null): LengthAwarePaginator
    {
        $queryBuilder = QueryBuilder::for(Snapshot::class);
        $queryBuilder->where('state', '!=', SnapshotStateEnum::UNPUBLISHED->value);

        if (class_basename($parent) == 'Banner') {
            $queryBuilder->where('parent_id', $parent->id)->where('parent_type', 'Banner');
        }

        if (class_basename($parent) == 'Webpage') {
            $queryBuilder->where('parent_id', $parent->id)->where('parent_type', 'Webpage');
        }

        return $queryBuilder
            ->defaultSort('-published_at')
            ->allowedSorts(['published_at', 'published_until'])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(Banner|Webpage $parent, ?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
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
                        'title' => __('Banner has not been published yet'),
                        'count' => 0
                    ]
                );
            if ($exportLinks) {
                $table->withExportLinks($exportLinks);
            }


            $table->column(key: 'state', label: ['fal', 'fa-yin-yang'], type: 'icon')
                ->column(key: 'publisher', label: __('publisher'), sortable: true)
                ->column(key: 'published_at', label: __('date published'), sortable: true)
                ->column(key: 'published_until', label: __('published until'))
                ->column(key: 'comment', label: __('comment'))
                ->column(key: 'recyclable', label: ['fal', 'fa-recycle'])
                ->defaultSort('published_at');
        };
    }


}
