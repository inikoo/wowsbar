<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 21 Mar 2023 19:17:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Media;

use App\Models\Tenancy\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;


class Media extends BaseMedia
{


    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)->withTimestamps();
    }
}
