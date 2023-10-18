<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Models\Market\Shop;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydratePaymentAccounts
{
    use AsAction;

    public function handle(Shop $shop): void
    {
        $stats = [
            'number_payment_service_providers' => $shop->paymentServiceProviders()->count(),
            'number_payment_accounts'          => $shop->paymentAccounts()->count(),
        ];

        $shop->accountingStats()->update($stats);
    }

    public function getJobUniqueId(Shop $shop): string
    {
        return $shop->id;
    }
}
