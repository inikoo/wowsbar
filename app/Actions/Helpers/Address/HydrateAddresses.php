<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Feb 2023 13:01:38 Malaysia Time, Ubud
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Address;

use App\Actions\Helpers\Address\Hydrators\AddressHydrateUsage;
use App\Actions\HydrateModel;
use App\Models\Helpers\Address;
use Illuminate\Support\Collection;

class HydrateAddresses extends HydrateModel
{
    public string $commandSignature = 'hydrate:addresses {slugs?*}';


    public function handle(Address $address): void
    {
        AddressHydrateUsage::run($address);
    }

    protected function getModel($id): Address
    {
        return Address::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Address::get();
    }
}
