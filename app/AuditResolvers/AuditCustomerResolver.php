<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 12 Oct 2023 15:56:49 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class AuditCustomerResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {


        if (config('global.customer_id')) {
            return config('global.customer_id');
        }

        if($auditable->customer_id) {
            return $auditable->customer_id;
        }

        return null;
    }
}
