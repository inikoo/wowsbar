<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 12 Oct 2023 18:53:55 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\AuditResolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

class AuditCustomerUserResolver implements Resolver
{
    public static function resolve(Auditable $auditable)
    {
        if (config('global.customer_user_id')) {
            return config('global.customer_user_id');
        }
        return null;
    }
}
