<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:57:52 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\History;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class HistoryResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        $naturalLanguage =$this['auditable_type'] .' '.$this['event'];


        return [
            'ip_address'         => $this['ip_address'],
            'created_at'         => $this['created_at'],
            'url'                => $this['url'],
            'type'               => $this['type'],
            'old_values'         => $this['old_values'],
            'new_values'         => $this['new_values'],
            'event'              => $this['event'],
            'auditable_id'       => $this['auditable_id'],
            'auditable_type'     => $this['auditable_type'],
            'user_id'            => Arr::get($this['user'], 'username') ?? __('Command Line'),
            'user_type'          => $this['user_type'],
            'slug'               => $this['slug'],
            'user_name'          => $this['user_name'],
            'tags'               => $this['tags'],
            'natural_language'   => $naturalLanguage
        ];
    }
}
