<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:06:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Subscriptions\CustomerWebsite\Hydrators;

use App\Models\Portfolios\CustomerWebsite;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerWebsiteHydrateUniversalSearch
{
    use AsAction;

    public function handle(CustomerWebsite $customerWebsite): void
    {

        $customerWebsite->universalSearch()->create(
            [
                'in_organisation' => true,
                'shop_id'         => $customerWebsite->customer->shop_id,
                'website_id'      => $customerWebsite->customer->website_id,
                'customer_id'     => $customerWebsite->customer_id,
                'section'         => 'customer-websites',
                'title'           => trim($customerWebsite->code.' '.$customerWebsite->name),
                'description'     => $customerWebsite->domain
            ]
        );
    }

}
