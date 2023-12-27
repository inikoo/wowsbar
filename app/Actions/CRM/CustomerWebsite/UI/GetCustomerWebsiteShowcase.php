<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Dec 2023 20:31:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\CustomerWebsite\UI;

use App\Http\Resources\Catalogue\BasketResource;
use App\Models\CRM\CustomerWebsite;
use Lorisleiva\Actions\Concerns\AsObject;

class GetCustomerWebsiteShowcase
{
    use AsObject;

    public function handle(CustomerWebsite $customerWebsite): array
    {
        $divisions = $customerWebsite->divisions()->orderBy('id', 'DESC')->get();

        return [
            'basket' => BasketResource::collection($divisions)
        ];
    }
}
