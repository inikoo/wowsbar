<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:06:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UniversalSearch\Organisation;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $name
 * @property string $code
 * @property string $domain
 */
class CustomerWebsiteSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'code'   => $this->code,
            'name'   => $this->name,
            'domain' => $this->domain,
            'route'  => [
                'name'       => 'org.subscriptions.websites.show',
                'parameters' => $this->slug
            ],
            'icon'   => ['fal', 'fa-globe']


        ];
    }
}
