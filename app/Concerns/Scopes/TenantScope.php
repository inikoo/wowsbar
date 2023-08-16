<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 14 Jul 2023 20:27:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Concerns\Scopes;

use App\Models\Tenancy\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Spatie\Multitenancy\Exceptions\NoCurrentTenant;

class TenantScope implements Scope
{
    /**
     * @throws \Spatie\Multitenancy\Exceptions\NoCurrentTenant
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! $tenant = Tenant::current()) {
            throw new NoCurrentTenant();
        }

        $builder->where($model->getTable().'.tenant_id', $tenant->id);
    }
}
