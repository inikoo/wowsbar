<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider;

use App\Actions\Accounting\PaymentServiceProvider\Hydrators\PaymentServiceProviderHydrateAccounts;
use App\Actions\Accounting\PaymentServiceProvider\Hydrators\PaymentServiceProviderHydratePayments;
use App\Actions\HydrateModel;
use App\Models\Accounting\PaymentServiceProvider;
use Illuminate\Support\Collection;

class HydratePaymentServiceProvider extends HydrateModel
{
    public string $commandSignature = 'hydrate:payment-service-providers {tenants?*} {--i|id=} ';

    public function handle(PaymentServiceProvider $paymentServiceProvider): void
    {
        PaymentServiceProviderHydrateAccounts::run($paymentServiceProvider);
        PaymentServiceProviderHydratePayments::run($paymentServiceProvider);
    }


    protected function getModel(int $id): PaymentServiceProvider
    {
        return PaymentServiceProvider::find($id);
    }

    protected function getAllModels(): Collection
    {
        return PaymentServiceProvider::withTrashed()->get();
    }
}
