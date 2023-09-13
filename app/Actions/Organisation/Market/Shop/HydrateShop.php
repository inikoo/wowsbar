<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 09 Feb 2022 15:04:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Organisation\Market\Shop;

use App\Actions\HydrateModel;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateCustomerInvoices;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateCustomers;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateDepartments;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateInvoices;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateOrders;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydratePaymentAccounts;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydratePayments;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateProducts;
use App\Actions\Organisation\Market\Shop\Hydrators\ShopHydrateSales;
use App\Models\Organisation\Market\Shop;
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
