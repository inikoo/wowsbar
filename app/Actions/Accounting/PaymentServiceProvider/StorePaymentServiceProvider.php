<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider;

use App\Models\Accounting\PaymentServiceProvider;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePaymentServiceProvider
{
    use AsAction;
    use WithAttributes;
    private bool $asAction=false;

    public function handle(array $modelData): PaymentServiceProvider
    {

        /** @var PaymentServiceProvider $paymentServiceProvider */
        $paymentServiceProvider = PaymentServiceProvider::create($modelData);

        $paymentServiceProvider->stats()->create();
        return $paymentServiceProvider;
    }





}
