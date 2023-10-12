<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 16:00:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerWebsite\UI;

use App\Http\Resources\Catalogue\BasketResource;
use App\Models\Catalogue\ProductCategory;
use App\Models\Portfolios\CustomerWebsite;
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
