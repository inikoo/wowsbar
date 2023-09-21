<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop;

use App\Actions\HydrateModel;
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
use Illuminate\Support\Collection;

class HydrateShop extends HydrateModel
{
    public string $commandSignature = 'hydrate:shop {tenants?*} {--i|id=} ';


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


    protected function getModel(int $id): Shop
    {
        return Shop::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Shop::withTrashed()->get();
    }
}
