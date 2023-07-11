<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:57:52 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\History;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class HistoryResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'ip_address'     => $this['ip_address'],
            'datetime'       => $this['datetime'],
            'url'            => $this['url'],
            'type'           => $this['type'],
            'tenant'         => app('currentTenant')->slug,
            'old_values'     => $this['old_values'],
            'new_values'     => $this['new_values'],
            'event'          => $this['event'],
            'auditable_id'   => $this['auditable_id'],
            'auditable_type' => $this['auditable_type'],
            'user_id'        => $this['user_id'],
            'user_type'      => $this['user_type'],
            'slug'           => $this['slug'],
            'user_name'      => $this['user_name'],
            'tags'           => $this['tags']
        ];
    }
}
