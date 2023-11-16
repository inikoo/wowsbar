<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 16 Nov 2023 15:44:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class AuditBulkIdResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {
        if (!empty($auditable->data)) {
            return Arr::get($auditable->data, 'bulk_import.id');
        }

        return null;
    }
}
