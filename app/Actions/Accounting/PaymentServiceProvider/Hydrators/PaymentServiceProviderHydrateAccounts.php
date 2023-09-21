<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider\Hydrators;

use App\Models\Accounting\PaymentServiceProvider;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Lorisleiva\Actions\Concerns\AsAction;

class PaymentServiceProviderHydrateAccounts implements ShouldBeUnique
{
    use AsAction;


    public function handle(PaymentServiceProvider $paymentServiceProvider): void
    {
        $stats=[
            'number_accounts'=> $paymentServiceProvider->accounts()->count()
        ];
        $paymentServiceProvider->stats()->update($stats);
    }

    public function getJobUniqueId(PaymentServiceProvider $paymentServiceProvider): int
    {
        return $paymentServiceProvider->id;
    }
}
