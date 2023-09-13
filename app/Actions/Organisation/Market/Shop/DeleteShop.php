<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 24 Jun 2023 13:12:05 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop;

use App\Actions\Tenancy\Tenant\Hydrators\TenantHydrateMarket;
use App\Models\Market\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteShop
{
    use AsController;
    use WithAttributes;

    public function handle(Shop $shop): Shop
    {
        $shop->website()->delete();
        $shop->prospects()->delete();
        $shop->products()->delete();
        $shop->departments()->delete();
        $shop->delete();
        TenantHydrateMarket::dispatch(app('currentTenant'));
        return $shop;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("shops.edit");
    }

    public function asController(Shop $shop, ActionRequest $request): Shop
    {
        $request->validate();

        return $this->handle($shop);
    }

    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('shops.index');
    }

}
