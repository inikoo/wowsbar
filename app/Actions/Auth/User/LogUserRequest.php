<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Elasticsearch\IndexElasticsearchDocument;
use App\Models\Auth\OrganisationUser;
use App\Models\Auth\User;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;
use Stevebauman\Location\Facades\Location;

class LogUserRequest
{
    use AsAction;

    public function handle(Carbon $datetime, array $routeData, string $ip, string $userAgent, string $type, User|OrganisationUser $user): void
    {
        $parent = organisation();

        if(class_basename($user) == 'User') {
            $parent = customer();
        }

        switch (class_basename($parent)) {
            case 'Customer':
                $slug = $parent->slug;
                $name = 'user';
                break;
            default:
                $slug = $parent->code;
                $name = 'user_org';
        }

        $index = config('elasticsearch.index_prefix'). $name . '_requests_' . $slug;

        $parsedUserAgent = (new Browser())->parse($userAgent);

        $body = [
            'type'          => $type,
            'datetime'      => $datetime,
            'customer'      => $slug,
            'username'      => $user->username,
            'route'         => $routeData,
            'module'        => explode('.', $routeData['name'])[0],
            'ip_address'    => $ip,
            'location'      => json_encode($this->getLocation($ip)), // reference: https://github.com/stevebauman/location
            'user_agent'    => $userAgent,
            'device_type'   => json_encode([
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

        // if platform=='Windows 10' need to check if it is actually Windows 11 see:
        // https://developers.whatismybrowser.com/learn/browser-detection/client-hints/detect-windows-11-client-hints
        // https://stackoverflow.com/questions/68614445/how-to-detect-windows-11-from-user-agent


        IndexElasticsearchDocument::dispatch(index: $index, body: $body);
        $user->stats->update(['last_active_at' => $datetime]);
    }

    public function getDeviceIcon($deviceType): string
    {
        if ($deviceType == 'Desktop') {
            return 'far fa-desktop-alt';
        }

        return 'fas fa-mobile-alt';
    }

    public function getBrowserIcon($browser): string
    {
        if (explode(' ', $browser)[0] == 'chrome') {
            return 'fab fa-chrome';
        } else {
            if ($browser == 'microsoft') {
                return 'fab fa-edge';
            }
        }

        return 'fab fa-firefox-browser';
    }

    public function getPlatformIcon($platform): string
    {
        if ($platform == 'android') {
            return 'fab fa-android';
        } else {
            if ($platform == 'apple') {
                return 'fab fa-apple';
            }
        }

        return 'fab fa-windows';
    }

    public function getLocation(string $ip): false|array|null
    {
        if ($position = Location::get($ip == '127.0.0.1' ? '103.121.18.96' : $ip)) {
            return [
                $position->countryCode,
                $position->countryName,
                $position->cityName
            ];
        }

        return false;
    }

    public function detectWindows11($parsedUserAgent): string
    {
        if ($parsedUserAgent->isWindows()) {
            if (str_contains($parsedUserAgent->userAgent(), 'Windows NT 10.0; Win64; x64')) {
                return 'Windows 11';
            }

            return 'Windows 10';
        }

        return $parsedUserAgent->platformName();
    }
}
