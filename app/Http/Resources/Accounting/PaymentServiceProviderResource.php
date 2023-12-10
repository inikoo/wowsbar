<?php
/*
 *  Author: Jonathan lopez <raul@inikoo.com>
 *  Created: Sat, 22 Oct 2022 18:53:15 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, inikoo
 */

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentServiceProviderResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\SysAdmin\Accounting\PaymentServiceProvider $paymentServiceProvider */
        $paymentServiceProvider=$this;
        return [

            'code'            => $paymentServiceProvider->code,
            'name'            => $paymentServiceProvider->name,
            'created_at'      => $paymentServiceProvider->created_at,
            'updated_at'      => $paymentServiceProvider->updated_at,
        ];
    }
}
