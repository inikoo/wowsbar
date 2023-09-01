<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:03:37 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Snapshot\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolio;
use App\Http\Resources\Gallery\ImageResource;
use App\Http\Resources\Portfolio\SnapshotResource;
use App\InertiaTable\InertiaTable;
use App\Models\Media\LandlordMedia;
use App\Models\Portfolio\Snapshot;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexSnapshots extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.images.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle();
    }



    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
//        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
//            $query->where(function ($query) use ($value) {
//                $query->whereAnyWordStartWith('media.name', $value);
//            });
//        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Snapshot::class);

        return $queryBuilder
            ->defaultSort('published_at')
            ->allowedSorts(['published_at','published_until'])
//            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
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
                        'count' => app('currentTenant')->snapshotStats->sum('number_snapshots')
                    ]
                )
                ->column(key: 'published_at', label: __('date published'), sortable: true)
                ->column(key: 'published_until', label: __('published until'))
                ->column(key: 'comment', label: __('comment'))
                ->column(key: 'state', label: __('type'))
                ->defaultSort('published_at');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return SnapshotResource::collection($this->handle());
    }
}
