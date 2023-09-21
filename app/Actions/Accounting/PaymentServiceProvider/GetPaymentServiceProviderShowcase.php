<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider;

use App\Models\Accounting\PaymentServiceProvider;
use Lorisleiva\Actions\Concerns\AsObject;

class GetPaymentServiceProviderShowcase
{
    use AsObject;

    public function handle(PaymentServiceProvider $warehouse): array
    {
        return [
            [

            ]
        ];
    }
}
