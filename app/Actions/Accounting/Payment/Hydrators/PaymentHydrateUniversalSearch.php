<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Payment\Hydrators;

use App\Models\Accounting\Payment;
use Lorisleiva\Actions\Concerns\AsAction;

class PaymentHydrateUniversalSearch
{
    use AsAction;


    public function handle(Payment $payment): void
    {
        $payment->universalSearch()->updateOrCreate(
            [],
            [
                'section' => 'accounting',
                'title'   => $payment->reference,
            ]
        );
    }

}
