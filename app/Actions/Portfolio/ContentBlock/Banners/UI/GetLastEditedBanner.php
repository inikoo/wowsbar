<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\ContentBlock\UI\IndexContentBlocks;
use App\Http\Resources\Portfolio\ContentBlockResource;
use App\Models\Portfolio\Website;
use App\Models\Tenancy\Tenant;
use App\Models\Web\WebBlockType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GetLastEditedBanner extends InertiaAction
{
    public function handle(Tenant|Website $parent, $prefix = null): ContentBlockResource
    {
        $result = $parent->contentBlocks()->latest('updated_at')->first();

        return new ContentBlockResource($result);
    }
}
