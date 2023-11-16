<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 08:34:13 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class AuditBulkTypeResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {
        if (!empty($auditable->data)) {
            return Arr::get($auditable->data, 'bulk_import.type');
        }

        return null;
    }
}
