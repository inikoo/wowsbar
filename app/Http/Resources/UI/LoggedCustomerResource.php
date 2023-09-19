<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 12:02:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UI;

use App\Http\Resources\HasSelfCall;
use App\Models\CRM\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

class LoggedCustomerResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Customer $customer */
        $customer = $this;

        return [
            'slug' => $customer->slug,
            'name' => $customer->name,
        ];
    }
}
