<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 14 Jul 2023 20:27:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Concerns;

use App\Concerns\Scopes\CustomerScope;
use App\Models\CRM\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToCustomer
{
    public static function bootBelongsToCustomer(): void
    {
        static::addGlobalScope(new CustomerScope());

        static::creating(function (Model $model) {
            $model->customer_id ??= config('global.customer_id');
        });

    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
