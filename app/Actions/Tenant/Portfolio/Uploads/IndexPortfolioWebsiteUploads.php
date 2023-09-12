<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use App\Http\Resources\Portfolio\WebsiteUploadsResource;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\WebsiteUpload;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexPortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('website_uploads.original_filename', $value)
                    ->orWhere('website_uploads.original_filename', 'ilike', "%$value%")
                    ->orWhere('website_uploads.filename', 'ilike', "$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(WebsiteUpload::class);

        return $queryBuilder
            ->defaultSort('website_uploads.original_filename')
            ->allowedSorts(['original_filename', 'filename', 'number_rows'])
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
                        'title' => __('No uploaded websites found'),
                        'count' => 0,
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'original_filename', label: __('Original Filename'), sortable: true)
                ->column(key: 'filename', label: __('Filename'), sortable: true)
                ->column(key: 'number_rows', label: __('Number Rows'), sortable: true)
                ->defaultSort('original_filename');
        };
    }

    public function jsonResponse(Collection $websiteUploads): AnonymousResourceCollection
    {
        return WebsiteUploadsResource::collection($websiteUploads);
    }

    public function asController(): LengthAwarePaginator
    {
        return $this->handle();
    }
}
