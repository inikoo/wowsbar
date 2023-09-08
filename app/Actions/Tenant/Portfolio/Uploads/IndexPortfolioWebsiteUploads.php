<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use App\Http\Resources\Portfolio\WebsiteUploadsResource;
use App\Models\WebsiteUpload;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class IndexPortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    public function handle(): WebsiteUpload
    {
        return app('currentTenant')->portfolioWebsiteUploads()->limit(5)->get();
    }

    public function jsonResponse(WebsiteUpload $websiteUploads): AnonymousResourceCollection
    {
        return WebsiteUploadsResource::collection($websiteUploads);
    }

    public function asController(): WebsiteUpload
    {
        return $this->handle();
    }
}
