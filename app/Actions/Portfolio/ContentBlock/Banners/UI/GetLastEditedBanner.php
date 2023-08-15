<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Tenancy\Tenant;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetLastEditedBanner extends InertiaAction
{
    public function handle(Tenant|PortfolioWebsite $parent, $prefix = null): AnonymousResourceCollection
    {
        $responses = $parent->contentBlocks()->limit(3)->latest('updated_at')->get();

        return ContentBlockResource::collection($responses);
    }
}
