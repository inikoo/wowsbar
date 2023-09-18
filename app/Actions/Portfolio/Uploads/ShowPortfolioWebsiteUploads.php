<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Uploads;

use App\Http\Resources\Portfolio\WebsiteUploadedRecordResource;
use App\InertiaTable\InertiaTable;
use App\Models\Media\ExcelUploadRecord;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ShowPortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->orWhere('website_upload_records.data', 'ilike', "$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(ExcelUploadRecord::class);

        return $queryBuilder
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
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
                        'title' => __('No websites found'),
                        'count' => 0,
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'code', label: __('Code'), sortable: true)
                ->column(key: 'name', label: __('Name'), sortable: true)
                ->column(key: 'domain', label: __('Domain'), sortable: true)
                ->defaultSort('code');
        };
    }

    public function jsonResponse(Collection $websiteUploads): AnonymousResourceCollection
    {
        return WebsiteUploadedRecordResource::collection($websiteUploads);
    }

    public function asController(): LengthAwarePaginator
    {
        return $this->handle();
    }
}
