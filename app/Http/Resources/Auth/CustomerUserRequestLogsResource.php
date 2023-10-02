<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Auth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use JsonSerializable;

class CustomerUserRequestLogsResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {

        return [
            'slug'            => $this['slug'],
            'ip_address'      => $this['ip_address'],
            'route_name'      => $this['route_name'],
            'route_parameter' => $this['arguments'],
            'module'          => Str::title($this['module']),
            'datetime'        => $this['datetime'],
            'location'        => $this['location'],
            'user_agent'      => [
                $this['device_type'],
                $this['platform'],
                $this['browser']
            ],
            'url'             => $this['url'],
        ];
    }
}
