<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Market\Shop\Hydrators\ShopHydrateProducts;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Market\Product;
use App\Models\Market\Shop;
use App\Models\Tenancy\Tenant;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;

class DeleteProduct
{
    use WithActionUpdate;

    public string $commandSignature = 'delete:product {tenant} {id}';

    public function handle(Product $product, array $deletedData = [], bool $skipHydrate = false): Product
    {
        $product->delete();
        $product->historicRecords()->delete();
        $product = $this->update($product, $deletedData, ['data']);
        if (!$skipHydrate) {
            //todo fix this
            /*
            if ($product->family_id) {
                FamilyHydrateProducts::dispatch($product->family);
            }
            */
            ShopHydrateProducts::dispatch($product->shop);
        }

        return $product;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.edit");
    }

    public function asController(Product $product, ActionRequest $request): Product
    {
        $request->validate();

        return $this->handle($product);
    }

    public function inShop(Shop $shop, Product $product, ActionRequest $request): Product
    {
        $request->validate();

        return $this->handle($product);
    }

    public function asCommand(Command $command): int
    {
        Tenant::where('slug', $command->argument('tenant'))->first()->makeCurrent();
        $this->handle(Product::findOrFail($command->argument('id')));
        return 0;
    }

    public function htmlResponse(Product $product): RedirectResponse
    {
        return Redirect::route('shops.show', $product->shop->slug);
    }
}
