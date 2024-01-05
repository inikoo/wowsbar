<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Shipping\ShipperAccount;

use App\Enums\CRM\Shipping\ShippingStateEnum;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Shipment;
use App\Models\Shipper;
use App\Models\ShipperAccount;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Throwable;

class StoreLabel
{
    use AsAction;
    use WithAttributes;

    public function handle(ShipperAccount $shipperAccount, array $modelData): ShipperAccount
    {
        $shipmentData = [];
        if (Arr::get($shipperAccount->data, 'debug') == 'Yes') {
            $shipmentData = [
                'debug' => [
                    'original_request' => $modelData
                ]
            ];
        }

        $shipmentData['callback_url'] = Arr::get($modelData, 'callback_url');

        $shipment = $shipperAccount->shipments()->create(
            [
                'status'    => ShippingStateEnum::PENDING,
                'reference' => Arr::get($modelData, 'reference'),
                'data'      => $shipmentData
            ]
        );

        return $shipperAccount->shipper->provider->createLabel($shipment, $request, $this);
    }
}
