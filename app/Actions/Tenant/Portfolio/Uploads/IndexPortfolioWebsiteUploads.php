<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use App\Http\Resources\Portfolio\WebsiteUploadsResource;
use App\InertiaTable\InertiaTable;
use App\Models\WebsiteUpload;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class IndexPortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    public function handle(): Collection
    {
        /** @var \App\Models\Tenancy\Tenant $tenant */
        $tenant = app('currentTenant');
        return $tenant->portfolioWebsiteUploads()->orderByDesc('id')->limit(4)->get();
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
                        'count' => app('currentTenant')->stats->number_websites,

                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'slug', label: __('code'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'domain', label: __('domain'), sortable: true)
                ->column(key: 'number_banners', label: __('banners'), sortable: true)
                ->defaultSort('slug');
        };
    }

    public function jsonResponse(Collection $websiteUploads): AnonymousResourceCollection
    {
        return WebsiteUploadsResource::collection($websiteUploads);
    }

    public function asController(): Collection
    {
        return $this->handle();
    }
}
