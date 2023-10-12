<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 11 Oct 2023 17:06:28 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser;

use App\Actions\Elasticsearch\IndexElasticsearchDocument;
use App\Actions\Traits\WithLogRequest;
use App\Models\Auth\CustomerUser;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogCustomerUserRequest
{
    use AsAction;
    use WithLogRequest;

    public function handle(Carbon $datetime, array $routeData, string $ip, string $customerUserAgent, string $type, CustomerUser $customerUser): void
    {
        $index = config('elasticsearch.index_prefix').'customer_users_requests';

        $parsedUserAgent = (new Browser())->parse($customerUserAgent);

        $body = [
            'type'               => $type,
            'datetime'           => $datetime,
            'customer'           => $customerUser->customer->slug,
            'username'           => $customerUser->user->email,
            'customer_user_slug' => $customerUser->slug,
            'customer_user_id'   => $customerUser->id,
            'customer_id'        => $customerUser->customer->id,
            'route'              => $routeData,
            'module'             => explode('.', $routeData['name'])[0],
            'ip_address'         => $ip,
            'location'           => json_encode($this->getLocation($ip)), // reference: https://github.com/stevebauman/location
            'user_agent'         => $customerUserAgent,
            'device_type'        => json_encode([
                'title' => $parsedUserAgent->deviceType(),
                'icon'  => $this->getDeviceIcon($parsedUserAgent->deviceType())
            ]),
            'platform'           => json_encode([
                'title' => $this->detectWindows11($parsedUserAgent),
                'icon'  => $this->getPlatformIcon($this->detectWindows11($parsedUserAgent))
            ]),
            'browser'            => json_encode([
                'title' => explode(' ', $parsedUserAgent->browserName())[0],
                'icon'  => $this->getBrowserIcon(strtolower($parsedUserAgent->browserName()))
            ])
        ];


        IndexElasticsearchDocument::dispatch(index: $index, body: $body);
        $customerUser->user->stats->update(['last_active_at' => $datetime]);
    }


}
