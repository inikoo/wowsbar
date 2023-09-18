<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 14 Jul 2023 20:27:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Concerns\Scopes;

use App\Exceptions\NoCustomer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CustomerScope implements Scope
{
    /**
     * @throws \App\Exceptions\NoCustomer
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! config('global.customer_id')) {
            throw new NoCustomer();
        }

        $builder->where($model->getTable().'.customer_id', config('global.customer_id'));
    }
}
