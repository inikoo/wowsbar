<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetLastEditedBanner extends InertiaAction
{
    public function handle(Tenant|PortfolioWebsite $parent, $prefix = null): AnonymousResourceCollection
    {
        $responses = $parent->banners()->limit(3)->latest('updated_at')->get();

        return BannerResource::collection($responses);
    }
}
