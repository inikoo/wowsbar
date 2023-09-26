<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Catalogue\Product;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;

class DeleteProduct
{
    use WithActionUpdate;


    public function handle(Product $product, array $deletedData = [], bool $skipHydrate = false): Product
    {
        $product->delete();
        $product = $this->update($product, $deletedData, ['data']);
        if (!$skipHydrate) {
            //todo fix this
            /*
            if ($product->family_id) {
                FamilyHydrateProducts::dispatch($product->family);
            }
            */
           // OrganisationHydrateProducts::dispatch();
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

    public string $commandSignature = 'delete:product {product}';

    public function asCommand(Command $command): int
    {
        $this->handle(Product::where('slug',$command->argument('product'))->firstOrFail());
        return 0;
    }

    public function htmlResponse(Product $product): RedirectResponse
    {
        return Redirect::route('shops.show', $product->shop->slug);
    }
}
