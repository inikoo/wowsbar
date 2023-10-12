<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 12 Oct 2023 08:57:05 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser;

use App\Actions\Elasticsearch\IndexElasticsearchDocument;
use App\Actions\Traits\WithLogRequest;
use App\Models\Auth\OrganisationUser;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogOrganisationUserRequest
{
    use AsAction;
    use WithLogRequest;

    public function handle(Carbon $datetime, array $routeData, string $ip, string $organisationUserAgent, string $type, OrganisationUser $organisationUser): void
    {
        $index = config('elasticsearch.index_prefix').'organisation_users_requests';

        $parsedUserAgent = (new Browser())->parse($organisationUserAgent);

        $body = [
            'type'                 => $type,
            'datetime'             => $datetime,
            'username'             => $organisationUser->username,
            'organisation_user_id' => $organisationUser->id,
            'route'                => $routeData,
            'module'               => explode('.', $routeData['name'])[0],
            'ip_address'           => $ip,
            'location'             => json_encode($this->getLocation($ip)), // reference: https://github.com/stevebauman/location
            'user_agent'           => $organisationUserAgent,
            'device_type'          => json_encode([
                'title' => $parsedUserAgent->deviceType(),
                'icon'  => $this->getDeviceIcon($parsedUserAgent->deviceType())
            ]),
            'platform'             => json_encode([
                'title' => $this->detectWindows11($parsedUserAgent),
                'icon'  => $this->getPlatformIcon($this->detectWindows11($parsedUserAgent))
            ]),
            'browser'              => json_encode([
                'title' => explode(' ', $parsedUserAgent->browserName())[0],
                'icon'  => $this->getBrowserIcon(strtolower($parsedUserAgent->browserName()))
            ])
        ];


        IndexElasticsearchDocument::dispatch(index: $index, body: $body);
        $organisationUser->stats->update(['last_active_at' => $datetime]);
    }


}
