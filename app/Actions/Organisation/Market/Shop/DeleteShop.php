<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 24 Jun 2023 13:12:05 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Market\Shop;

use App\Models\Organisation\Market\Shop;
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
        $shop->delete();
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
        return Redirect::route('org.shops.index');
    }

}
