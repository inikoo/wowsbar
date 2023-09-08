<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 21 Feb 2022 23:37:01 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Resolvers;

use App\Models\Tenancy\Tenant;
use Illuminate\Http\Request;

use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class TenantResolver extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {

        $subdomain = current(explode('.', $request->getHost()));
        if ($subdomain=='www') {
            return null;
        }
        if ($subdomain=='assets') {

            return null;
        }

        return  Tenant::where('slug', $subdomain)->first();
    }
}
