<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop;

use App\Actions\Market\Shop\Hydrators\ShopHydratePaymentAccounts;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Accounting\PaymentAccount;
use App\Models\Market\Shop;

class AttachPaymentAccountToShop
{
    use WithActionUpdate;

    public function handle(Shop $shop, PaymentAccount $paymentAccount): Shop
    {
        $shop->paymentAccounts()->attach(
            $paymentAccount,
            [
                'currency_id' => $shop->currency_id
            ]
        );

        $shop->paymentServiceProviders()->attach(
            $paymentAccount->paymentServiceProvider,
            [
                'currency_id' => $shop->currency_id
            ]
        );

        ShopHydratePaymentAccounts::run($shop);

        return $shop;
    }
}
