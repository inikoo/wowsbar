<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentAccount;

use App\Actions\Accounting\PaymentAccount\Hydrators\PaymentAccountHydratePayments;
use App\Actions\HydrateModel;
use App\Models\Accounting\PaymentAccount;
use Illuminate\Support\Collection;

class HydratePaymentAccounts extends HydrateModel
{
    public string $commandSignature = 'hydrate:payment-accounts {slugs?*} ';

    public function handle(PaymentAccount $paymentAccount): void
    {
        PaymentAccountHydratePayments::run($paymentAccount);
    }


    protected function getModel(string $slug): PaymentAccount
    {
        return PaymentAccount::firstWhere('slug', $slug);
    }

    protected function getAllModels(): Collection
    {
        return PaymentAccount::withTrashed()->get();
    }
}
