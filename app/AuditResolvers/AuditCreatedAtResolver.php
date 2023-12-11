<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Dec 2023 11:52:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class AuditCreatedAtResolver implements Resolver
{
    public static function resolve(Auditable $auditable): ?Carbon
    {
        if (!empty($auditable->data) && Arr::get($auditable->data, 'bulk_import.type') === 'Fetch' && $auditable->created_at) {
            return $auditable->created_at;
        }

        return now();
    }
}
