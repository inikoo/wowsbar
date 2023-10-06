<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:55:05 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Snapshot\UI;

use App\Actions\InertiaAction;
use App\Http\Resources\Portfolio\SnapshotResource;
use App\InertiaTable\InertiaTable;
use App\Models\Helpers\Snapshot;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\QueryBuilder;

class IndexSnapshots extends InertiaAction
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
    public function handle($prefix = null): LengthAwarePaginator
    {
        $queryBuilder = QueryBuilder::for(Snapshot::class);

        return $queryBuilder
            ->defaultSort('published_at')
            ->allowedSorts(['published_at','published_until'])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
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
                        'title' => __('No snapshot found'),
                        'count' => 0
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'state', label: ['fal', 'fa-yin-yang'])
                ->column(key: 'published_at', label: __('date published'), sortable: true)
                ->column(key: 'published_until', label: __('published until'))
                ->column(key: 'comment', label: __('comment'))
                ->defaultSort('published_at');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return SnapshotResource::collection($this->handle());
    }
}