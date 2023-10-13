<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 09:38:55 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\History;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CustomerHistoryResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        $naturalLanguage = $this['auditable_type'].' '.$this['event'];


        return [


            'customer_user_slug' => $this['customer_user_slug'],
            'created_at'         => $this['created_at'],
            //'ip_address'         => $this['ip_address'],
            //'url'                => $this['url'],
            //'type'               => $this['type'],
            //'old_values'         => $this['old_values'],
            //'new_values'         => $this['new_values'],
            //'event'              => $this['event'],
            //'auditable_id'       => $this['auditable_id'],
            //'auditable_type'     => $this['auditable_type'],
            //'user_id'            => $this['user_id'],
            //'user_type'          => $this['user_type'],
            //'slug'               => $this['slug'],
            //'tags'               => $this['tags'],
            'natural_language'   => $naturalLanguage
        ];
    }
}
