<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use App\Http\Resources\Portfolio\WebsiteUploadsResource;
use App\Models\WebsiteUpload;
use Excel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class IndexPortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    public function handle()
    {
        return app('currentTenant')->portfolioWebsiteUploads()->limit(5)->get();
    }

    public function jsonResponse(WebsiteUpload $websiteUploads): AnonymousResourceCollection
    {
        return WebsiteUploadsResource::collection($websiteUploads);
    }

    public function asController()
    {
        return $this->handle();
    }
}
