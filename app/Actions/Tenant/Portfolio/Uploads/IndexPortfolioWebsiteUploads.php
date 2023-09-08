<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use App\Http\Resources\Portfolio\WebsiteUploadsResource;
use App\Models\WebsiteUpload;
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

    public function jsonResponse(Collection $websiteUploads): AnonymousResourceCollection
    {
        return WebsiteUploadsResource::collection($websiteUploads);
    }

    public function asController(): Collection
    {
        return $this->handle();
    }
}
