<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\LiveCustomersUsersCurrentPage;

use App\Models\Auth\CustomerUser;
use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Events\BroadcastLiveUsers;

class StoreCustomerLiveUsersCurrentPage
{
    use AsAction;


    public function handle(CustomerUser $customerUser, array $data): bool
    {
        $liveUsers = IndexLiveCustomerUsersCurrentPage::run();


        $liveUsers[$customerUser->id] = [
            'last_active' => now(),
            'current_page'=> $data
        ];

        return Cache::put('live_customer_users', $liveUsers);

        // $cache = Cache::put('live_organisation_users', $liveUsers);

        // broadcast(new BroadcastLiveUsers($liveUsers, $organisationUser))->toOthers();

        // return $cache;
    }


    public function rules(): array
    {
        return [
            'label' => 'required|string',
        ];
    }

    public function asController(CustomerUser $customerUser, ActionRequest $request): bool
    {
        return $this->handle($customerUser, $request->all());
    }
}
