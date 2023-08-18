<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 17:01:05 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetLastEditedBanner extends InertiaAction
{
    public function handle(Tenant|PortfolioWebsite $parent, $prefix = null): AnonymousResourceCollection
    {
        $responses = $parent->contentBlocks()->limit(3)->latest('updated_at')->get();

        return BannerResource::collection($responses);
    }
}
