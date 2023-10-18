<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 11:52:43 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Elasticsearch\IndexElasticsearchDocument;
use App\Actions\Traits\WithLogRequest;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\CustomerUser;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogUserLogout
{
    use AsAction;
    use WithLogRequest;

    public function handle(CustomerUser $customerUser, string $ip, string $userAgent, Carbon $datetime): void
    {

        $this->log($datetime, $ip, $userAgent, $customerUser);

    }


    public function log(Carbon $datetime, string $ip, string $userAgent, CustomerUser $customerUser): void
    {
        $index = config('elasticsearch.index_prefix').'customer_users_requests';

        $parsedUserAgent = (new Browser())->parse($userAgent);

        $body = [
            'type'               => ElasticsearchUserRequestTypeEnum::LOGOUT->value,
            'datetime'           => $datetime,
            'customer'           => $customerUser->customer->slug,
            'username'           => $customerUser->user->email,
            'customer_user_slug' => $customerUser->slug,
            'customer_user_id'   => $customerUser->id,
            'customer_id'        => $customerUser->customer->id,
            'user_id'            => $customerUser->user->id,
            'ip_address'         => $ip,
            'location'           => json_encode($this->getLocation($ip)), // reference: https://github.com/stevebauman/location
            'user_agent'         => $userAgent,
            'device_type'        => json_encode([
                'title' => $parsedUserAgent->deviceType(),
                'icon'  => $this->getDeviceIcon($parsedUserAgent->deviceType())
            ]),
            'platform'    => json_encode([
                'title' => $this->detectWindows11($parsedUserAgent),
                'icon'  => $this->getPlatformIcon($this->detectWindows11($parsedUserAgent))
            ]),
            'browser'     => json_encode([
                'title' => explode(' ', $parsedUserAgent->browserName())[0],
                'icon'  => $this->getBrowserIcon(strtolower($parsedUserAgent->browserName()))
            ])
        ];

        IndexElasticsearchDocument::dispatch(index: $index, body: $body);
    }


}
