<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 11:24:33 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Elasticsearch\IndexElasticsearchDocument;
use App\Actions\Traits\WithLogRequest;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\User;
use App\Models\Web\Website;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogUserFailLogin
{
    use AsAction;
    use WithLogRequest;

    public function handle(Website $website, array $credentials, string $ip, string $userAgent, Carbon $datetime): void
    {
        $user = User::where('email', Arr::get($credentials, 'email'))->first();


        $this->log($datetime, $ip, $userAgent, Arr::get($credentials, 'email'), $user?->id);

        if ($user) {
            $stats = [
                'number_failed_logins' => $user->stats->number_failed_logins + 1,
                'last_failed_login_ip' => $ip,
                'last_failed_login_at' => $datetime
            ];
            $user->stats()->update($stats);
        }

        $website->webStats()->update(
            [
                'number_failed_logins' => $website->webStats->number_failed_logins + 1,
                'last_failed_login_at' => $datetime
            ]
        );
    }


    public function log(Carbon $datetime, string $ip, string $userAgent, string $username, ?int $userId): void
    {
        $index = config('elasticsearch.index_prefix').'customer_users_requests';

        $parsedUserAgent = (new Browser())->parse($userAgent);

        $body = [
            'type'        => ElasticsearchUserRequestTypeEnum::FAIL_LOGIN->value,
            'datetime'    => $datetime,
            'username'    => $username,
            'user_id'     => $userId,
            'ip_address'  => $ip,
            'location'    => json_encode($this->getLocation($ip)), // reference: https://github.com/stevebauman/location
            'user_agent'  => $userAgent,
            'device_type' => json_encode([
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
