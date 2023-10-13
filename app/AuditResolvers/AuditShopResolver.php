<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 08:34:34 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class AuditShopResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {
        if($auditable->shop_id) {
            return $auditable->shop_id;
        }
        if(class_basename($auditable)=='Shop') {
            return $auditable->id;
        }

        return null;
    }
}
