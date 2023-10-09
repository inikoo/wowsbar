<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop;

use App\Actions\Market\Shop\Hydrators\ShopHydrateCustomerInvoices;
use App\Actions\Market\Shop\Hydrators\ShopHydrateCustomers;
use App\Actions\Market\Shop\Hydrators\ShopHydrateDepartments;
use App\Actions\Market\Shop\Hydrators\ShopHydrateInvoices;
use App\Actions\Market\Shop\Hydrators\ShopHydrateOrders;
use App\Actions\Market\Shop\Hydrators\ShopHydratePaymentAccounts;
use App\Actions\Market\Shop\Hydrators\ShopHydratePayments;
use App\Actions\Market\Shop\Hydrators\ShopHydrateProducts;
use App\Actions\Market\Shop\Hydrators\ShopHydrateSales;
use App\Models\Market\Shop;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class HydrateShop
{
    use asAction;

    public function handle(Shop $shop): void
    {
        ShopHydratePaymentAccounts::run($shop);
        ShopHydratePayments::run($shop);
        ShopHydrateCustomers::run($shop);
        ShopHydrateCustomerInvoices::run($shop);
        ShopHydrateOrders::run($shop);
        ShopHydrateDepartments::run($shop);
        ShopHydrateInvoices::run($shop);
        ShopHydrateSales::run($shop);
        ShopHydrateProducts::run($shop);
    }
    public string $commandSignature = 'hydrate:shops {shops?*} ';

    public function asCommand(Command $command): int
    {

        if(!$command->argument('shops')) {
            $shops=Shop::all();
        } else {
            $shops =  Shop::query()
                ->when($command->argument('shops'), function ($query) use ($command) {
                    $query->whereIn('slug', $command->argument('shops'));
                })
                ->cursor();
        }


        $exitCode = 0;

        foreach ($shops as $shop) {

            $this->handle($shop);
            $command->line("Shop {$shop->name} hydrated 💦");

        }

        return $exitCode;
    }

}
