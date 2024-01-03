<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\ShipperAccount\UI;

use App\Actions\InertiaAction;
use Lorisleiva\Actions\Concerns\AsObject;

class GetShipperOptions extends InertiaAction
{
    use AsObject;

    public function handle($shippers): array
    {
        $selectOptions = [];
        /** @var \App\Models\Shipper $shipper */
        foreach ($shippers as $shipper) {
            $selectOptions[$shipper->id] =
                [
                    'name' => $shipper->name,
                    'slug' => $shipper->slug,
                    'id'   => $shipper->id,
                ];
        }

        return $selectOptions;
    }
}
