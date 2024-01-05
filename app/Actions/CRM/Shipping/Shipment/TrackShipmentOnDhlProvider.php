<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Shipping\Shipment;

use App\Models\ShipperAccount;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class TrackShipmentOnDhlProvider
{
    use AsAction;
    use WithAttributes;

    public function handle(ShipperAccount $shipperAccount, array $modelData)
    {
        $response = Http::withBasicAuth(Arr::get($shipperAccount->data,
            'api_username'),
            Arr::get($shipperAccount->data, 'api_password'))
            ->get(Arr::get($shipperAccount->data, 'api_url'),
                $this->scheme($shipperAccount, $modelData));

        return $response->json();
    }
}
