<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 08:34:13 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class AuditWebsiteResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {
        if($auditable->website_id) {
            return $auditable->website_id;
        }

        if(class_basename($auditable)=='Website') {
            return $auditable->id;
        }

        return null;
    }
}
